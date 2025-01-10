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
    map: googleMap
});

// Add location search control
const locationSearchControl = L.control({ position: 'topleft' });
locationSearchControl.onAdd = function () {
    const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
    div.innerHTML = `
        <div class="bg-white p-2 rounded shadow-lg" style="min-width: 200px;">
            <input type="text" id="start-location" class="w-full p-2 border rounded mb-2"
                   placeholder="Enter your starting point">
            <button id="get-location" class="bg-blue-500 text-white px-4 py-2 rounded w-full mb-2">
                Use Current Location
            </button>
        </div>
    `;
    return div;
};
locationSearchControl.addTo(leafletMap);


// Add location search to Google Maps
const googleSearchBox = new google.maps.places.SearchBox(
    document.getElementById('start-location')
);


// Initialize variables for start location
let startLocation = null;

// Get current location
document.getElementById('get-location').addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                startLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                document.getElementById('start-location').value = `${startLocation.lat}, ${startLocation.lng}`;

                // Update both maps
                leafletMap.setView([startLocation.lat, startLocation.lng], 12);
                googleMap.setCenter(startLocation);
            },
            (error) => {
                console.error("Error getting location:", error);
                alert("Unable to get your location. Please enter it manually.");
            }
        );
    } else {
        alert("Geolocation is not supported by your browser.");
    }
});

// Function to calculate and display route
function calculateRoute(destination) {
    if (!startLocation) {
        alert("Please set a starting location first!");
        return;
    }

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
            L.latLng(destination.lat, destination.lng)
        ],
        routeWhileDragging: true,
        showAlternatives: true,
        fitSelectedRoutes: true,
        lineOptions: {
            styles: [{ color: '#0000ff', opacity: 0.6, weight: 6 }]
        }
    }).addTo(leafletMap);

    // Calculate route for Google Maps
    const request = {
        origin: startLocation,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, (result, status) => {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);
        } else {
            console.error("Error calculating route:", status);
        }
    });
}


// Create a single InfoWindow instance for Google Maps
const googleInfoWindow = new google.maps.InfoWindow();

// Store markers references
let googleMarkers = new Map(); // Using Map to store marker references
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

// Functions to create popup content remain the same
function createLeafletPopupContent(marker) {
    return `
        <div class="flex flex-col max-w-xs rounded-lg shadow-lg bg-white ">
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
            </div>
        </div>
    `;
}

function createGoogleMapsInfoContent(marker) {
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
            </div>
        </div>
    `;
}

// Function to clear existing markers
function clearMarkers() {
    // Clear Google markers
    googleMarkers.forEach((marker) => {
        marker.setMap(null);
    });
    googleMarkers.clear();

    // Clear Leaflet markers
    leafletMarkers.forEach((marker) => {
        leafletMap.removeLayer(marker);
    });
    leafletMarkers.clear();
}

// Function to add markers to both maps
async function addMarkersToMaps() {
    const markers = await fetchMarkers();

    // Clear existing markers first
    clearMarkers();

    markers.forEach((marker) => {
        // Add marker to Leaflet map
        const leafletMarker = L.marker([marker.latitude, marker.longitude])
            .addTo(leafletMap)
            .bindPopup(createLeafletPopupContent(marker));

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

    // Create popup content with route button
    const leafletContent = createLeafletPopupContent(marker) + `
        <div class="p-2 border-t">
            <button onclick="calculateRoute({lat: ${lat}, lng: ${lng}})"
                    class="bg-green-500 text-white px-4 py-2 rounded w-full">
                Get Directions
            </button>
        </div>
    `;

    // Show Leaflet popup
    if (leafletMarkers.has(marker.id)) {
        const leafletMarker = leafletMarkers.get(marker.id);
        leafletMarker.bindPopup(leafletContent).openPopup();
    }

    // Show Google Maps popup with route button
    if (googleMarkers.has(marker.id)) {
        const googleMarker = googleMarkers.get(marker.id);
        const googleContent = createGoogleMapsInfoContent(marker) + `
            <div class="p-2 border-t">
                <button onclick="calculateRoute({lat: ${lat}, lng: ${lng}})"
                        class="bg-green-500 text-white px-4 py-2 rounded w-full">
                    Get Directions
                </button>
            </div>
        `;
        googleInfoWindow.setContent(googleContent);
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
