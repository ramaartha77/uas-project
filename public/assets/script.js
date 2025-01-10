// Initialize both maps
let leafletMap = L.map("leaflet-map").setView(
    [-8.371760437036368, 115.19692080521014],
    9
);
let googleMap = new google.maps.Map(document.getElementById("google-map"), {
    center: { lat: -8.371760437036368, lng: 115.19692080521014 },
    zoom: 9,
});

// Add Leaflet tile layer (OpenStreetMap)
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
}).addTo(leafletMap);

// Initialize routing controls
let leafletRoutingControl = null;
const directionsService = new google.maps.DirectionsService();
const directionsRenderer = new google.maps.DirectionsRenderer({
    map: googleMap,
});

// Initialize location variables
let startLocation = null;
let endLocation = null;
let startAddress = "";
let endAddress = "";

// Initialize geocoder
const geocoder = new google.maps.Geocoder();

// Add location search to Google Maps
const googleSearchBox = new google.maps.places.SearchBox(
    document.getElementById("start-location")
);

// Function to update route information display
function updateRouteInfo() {
    const routeInfo = document.getElementById("route-info");
    const startAddressElement = document.getElementById("start-address");
    const endAddressElement = document.getElementById("end-address");

    if (startLocation || endLocation) {
        routeInfo.classList.remove("hidden");
        startAddressElement.textContent = startAddress || "Not set";
        endAddressElement.textContent = endAddress || "Not set";
    } else {
        routeInfo.classList.add("hidden");
    }
}

// Function to get address from coordinates
async function getAddressFromCoordinates(lat, lng) {
    return new Promise((resolve, reject) => {
        geocoder.geocode({ location: { lat, lng } }, (results, status) => {
            if (status === "OK") {
                if (results[0]) {
                    resolve(results[0].formatted_address);
                } else {
                    resolve(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                }
            } else {
                resolve(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
            }
        });
    });
}

// Get current location
document
    .getElementById("get-current-location")
    .addEventListener("click", async () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    startLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    startAddress = await getAddressFromCoordinates(
                        startLocation.lat,
                        startLocation.lng
                    );
                    document.getElementById("start-location").value =
                        startAddress;

                    // Update both maps
                    leafletMap.setView(
                        [startLocation.lat, startLocation.lng],
                        12
                    );
                    googleMap.setCenter(startLocation);

                    updateRouteInfo();
                },
                (error) => {
                    console.error("Error getting location:", error);
                    alert(
                        "Unable to get your location. Please enter it manually."
                    );
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    });

// Set manual location
document.getElementById("set-manual-location").addEventListener("click", () => {
    const locationInput = document.getElementById("start-location").value;
    if (locationInput) {
        geocoder.geocode(
            { address: locationInput },
            async (results, status) => {
                if (status === "OK" && results[0]) {
                    startLocation = {
                        lat: results[0].geometry.location.lat(),
                        lng: results[0].geometry.location.lng(),
                    };
                    startAddress = results[0].formatted_address;

                    leafletMap.setView(
                        [startLocation.lat, startLocation.lng],
                        12
                    );
                    googleMap.setCenter(startLocation);

                    updateRouteInfo();
                } else {
                    alert(
                        "Could not find the specified location. Please try again."
                    );
                }
            }
        );
    } else {
        alert("Please enter a starting location.");
    }
});

// Cancel route
document.getElementById("cancel-route").addEventListener("click", () => {
    // Clear routes
    if (leafletRoutingControl) {
        leafletMap.removeControl(leafletRoutingControl);
        leafletRoutingControl = null;
    }
    directionsRenderer.setMap(null);

    // Reset locations
    endLocation = null;
    endAddress = "";

    // Update display
    updateRouteInfo();
});

// Function to calculate and display route
async function calculateRoute(destination) {
    if (!startLocation) {
        alert("Please set a starting location first!");
        return;
    }

    endLocation = destination;
    endAddress = await getAddressFromCoordinates(
        destination.lat,
        destination.lng
    );

    // Clear existing routes
    if (leafletRoutingControl) {
        leafletMap.removeControl(leafletRoutingControl);
    }
    directionsRenderer.setMap(null);
    directionsRenderer.setMap(googleMap);

    // Calculate route for Leaflet
    leafletRoutingControl = L.Routing.control({
        waypoints: [
            L.latLng(startLocation.lat, startLocation.lng),
            L.latLng(destination.lat, destination.lng),
        ],
        routeWhileDragging: true,
        showAlternatives: true,
        fitSelectedRoutes: true,
        lineOptions: {
            styles: [{ color: "#0000ff", opacity: 0.6, weight: 6 }],
        },
    }).addTo(leafletMap);

    // Calculate route for Google Maps
    const request = {
        origin: startLocation,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING,
    };

    directionsService.route(request, (result, status) => {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);
            updateRouteInfo();
        } else {
            console.error("Error calculating route:", status);
        }
    });
}

// Create a single InfoWindow instance for Google Maps
const googleInfoWindow = new google.maps.InfoWindow();

// Store markers references
let googleMarkers = new Map();
let leafletMarkers = new Map();

// Function to fetch markers from the database
async function fetchMarkers() {
    try {
        const response = await fetch("/api/markers");
        if (!response.ok) {
            throw new Error("Failed to fetch markers.");
        }
        const markers = await response.json();
        return markers;
    } catch (error) {
        console.error("Error fetching markers:", error);
        return [];
    }
}

// Modified popup content creation functions to include route calculation
function createLeafletPopupContent(marker) {
    const lat = parseFloat(marker.latitude);
    const lng = parseFloat(marker.longitude);

    return `
        <div class="flex flex-col max-w-xs rounded-lg shadow-lg bg-white">
            ${
                marker.image
                    ? `
                <div class="relative w-full h-0 overflow-hidden" style="padding-bottom: 56.25%;">
                    <img src="/storage/${marker.image}" alt="${marker.name}" class="absolute top-0 left-0 w-full h-full object-cover overflow-hidden">
                </div>
            `
                    : ""
            }
            <div class="p-3 flex-1">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-md text-gray-800">${
                        marker.name
                    }</h3>
                    ${
                        marker.rate
                            ? `
                        <div class="flex items-center text-xs">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span class="text-gray-700">${marker.rate}/5</span>
                        </div>
                    `
                            : ""
                    }
                </div>
                ${
                    marker.description
                        ? `<p class="text-gray-700 text-xs">${marker.description}</p>`
                        : ""
                }
                ${
                    marker.address
                        ? `
                    <p class="text-gray-600 text-xs">
                        <i class="fas fa-map-marker-alt mr-1"></i> ${marker.address}
                    </p>
                `
                        : ""
                }
                ${
                    marker.price
                        ? `
                    <p class="text-green-600 font-semibold text-xs mt-2">
                        <i class="fas fa-tag mr-1"></i> Rp.${marker.price}
                    </p>
                `
                        : ""
                }
                <div class="mt-2 pt-2 border-t">
                    <button onclick="calculateRoute({lat: ${lat}, lng: ${lng}})"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full flex items-center justify-center">
                        <i class="fas fa-directions mr-2"></i>
                        Get Directions
                    </button>
                </div>
            </div>
        </div>
    `;
}

function createGoogleMapsInfoContent(marker) {
    const lat = parseFloat(marker.latitude);
    const lng = parseFloat(marker.longitude);

    return `
        <div class="flex flex-col max-w-xs rounded-lg shadow-lg bg-white">
            ${
                marker.image
                    ? `
                <div class="relative w-full h-0" style="padding-bottom: 56.25%;">
                    <img src="/storage/${marker.image}" alt="${marker.name}" class="absolute top-0 left-0 w-full h-full object-cover">
                </div>
            `
                    : ""
            }
            <div class="p-3 flex-1">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-md text-gray-800">${
                        marker.name
                    }</h3>
                    ${
                        marker.rate
                            ? `
                        <div class="flex items-center text-xs">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span class="text-gray-700">${marker.rate}/5</span>
                        </div>
                    `
                            : ""
                    }
                </div>
                ${
                    marker.description
                        ? `<p class="text-gray-700 text-xs">${marker.description}</p>`
                        : ""
                }
                ${
                    marker.address
                        ? `
                    <p class="text-gray-600 text-xs">
                        <i class="fas fa-map-marker-alt mr-1"></i> ${marker.address}
                    </p>
                `
                        : ""
                }
                ${
                    marker.price
                        ? `
                    <p class="text-green-600 font-semibold text-xs mt-2">
                        <i class="fas fa-tag mr-1"></i> Rp.${marker.price}
                    </p>
                `
                        : ""
                }
                <div class="mt-2 pt-2 border-t">
                    <button onclick="calculateRoute({lat: ${lat}, lng: ${lng}})"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full flex items-center justify-center">
                        <i class="fas fa-directions mr-2"></i>
                        Get Directions
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Function to clear existing markers
function clearMarkers() {
    googleMarkers.forEach((marker) => {
        marker.setMap(null);
    });
    googleMarkers.clear();

    leafletMarkers.forEach((marker) => {
        leafletMap.removeLayer(marker);
    });
    leafletMarkers.clear();
}

// Function to add markers to both maps
async function addMarkersToMaps() {
    const markers = await fetchMarkers();
    clearMarkers();

    markers.forEach((marker) => {
        // Add marker to Leaflet map
        const leafletMarker = L.marker([marker.latitude, marker.longitude])
            .addTo(leafletMap)
            .bindPopup(createLeafletPopupContent(marker));

        // Add click handler for Leaflet marker
        leafletMarker.on("click", () => {
            leafletMarker.openPopup();
        });

        leafletMarkers.set(marker.id, leafletMarker);

        // Add marker to Google Maps
        const googleMarker = new google.maps.Marker({
            position: {
                lat: parseFloat(marker.latitude),
                lng: parseFloat(marker.longitude),
            },
            map: googleMap,
            title: marker.name,
        });

        // Add click listener for Google Maps marker
        googleMarker.addListener("click", () => {
            googleInfoWindow.setContent(createGoogleMapsInfoContent(marker));
            googleInfoWindow.open(googleMap, googleMarker);
        });

        googleMarkers.set(marker.id, googleMarker);
    });

    // Center maps on the first marker
    if (markers.length > 0) {
        const firstMarker = markers[0];
        leafletMap.setView([firstMarker.latitude, firstMarker.longitude], 12);
        googleMap.setCenter({
            lat: parseFloat(firstMarker.latitude),
            lng: parseFloat(firstMarker.longitude),
        });
        googleMap.setZoom(12);
    }
}

// Function to show popup for specific marker
function showPopup(marker) {
    const lat = parseFloat(marker.latitude);
    const lng = parseFloat(marker.longitude);

    // Center both maps on the marker
    leafletMap.setView([lat, lng], 12);
    googleMap.setCenter({ lat, lng });
    googleMap.setZoom(12);

    // Show Leaflet popup
    if (leafletMarkers.has(marker.id)) {
        const leafletMarker = leafletMarkers.get(marker.id);
        leafletMarker.bindPopup(createLeafletPopupContent(marker)).openPopup();
    }

    // Show Google Maps popup
    if (googleMarkers.has(marker.id)) {
        const googleMarker = googleMarkers.get(marker.id);
        googleInfoWindow.setContent(createGoogleMapsInfoContent(marker));
        googleInfoWindow.open(googleMap, googleMarker);
    }
}

// Load markers when the page loads
document.addEventListener("DOMContentLoaded", () => {
    addMarkersToMaps();

    // Add event listeners for View buttons
    const viewButtons = document.querySelectorAll(".view-button");
    viewButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const marker = JSON.parse(button.getAttribute("data-marker"));
            showPopup(marker);
        });
    });
});
