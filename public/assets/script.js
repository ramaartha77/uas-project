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

// Function to fetch markers from the database
async function fetchMarkers() {
    try {
        const response = await fetch("/api/markers");
        const markers = await response.json();
        return markers;
    } catch (error) {
        console.error("Error fetching markers:", error);
        return [];
    }
}

// Function to create Leaflet popup content
function createLeafletPopupContent(marker) {
    return `
        <div class="flex flex-col max-w-xs rounded-lg shadow-lg bg-white">
            <!-- Image -->
            ${
                marker.image
                    ? `
                <div class="relative w-full h-0" style="padding-bottom: 56.25%;"> <!-- 16:9 Aspect Ratio -->
                    <img src="/storage/${marker.image}" alt="${marker.name}"
                         class="absolute top-0 left-0 w-full h-full object-cover">
                </div>
            `
                    : ""
            }
            <!-- Content -->
            <div class="p-3 flex-1"> <!-- Reduced padding -->
                <!-- Marker Name and Rating -->
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-md text-gray-800">${
                        marker.name
                    }</h3> <!-- Reduced font size -->
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
                <!-- Description -->
                ${
                    marker.description
                        ? `
                    <p class="text-gray-700 text-xs ">${marker.description}</p> <!-- Reduced font size -->
                `
                        : ""
                }
                <!-- Address -->
                ${
                    marker.address
                        ? `
                    <p class="text-gray-600 text-xs ">
                        <i class="fas fa-map-marker-alt mr-1"></i> ${marker.address}
                    </p>
                `
                        : ""
                }
                <!-- Price -->
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

// Function to create Google Maps info window content
function createGoogleMapsInfoContent(marker) {
    return `
    <div class="flex flex-col max-w-xs rounded-lg shadow-lg bg-white">
        <!-- Image -->
        ${
            marker.image
                ? `
            <div class="relative w-full h-0" style="padding-bottom: 56.25%;"> <!-- 16:9 Aspect Ratio -->
                <img src="/storage/${marker.image}" alt="${marker.name}"
                     class="absolute top-0 left-0 w-full h-full object-cover">
            </div>
        `
                : ""
        }
        <!-- Content -->
        <div class="p-3 flex-1"> <!-- Reduced padding -->
            <!-- Marker Name and Rating -->
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-md text-gray-800">${
                    marker.name
                }</h3> <!-- Reduced font size -->
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
            <!-- Description -->
            ${
                marker.description
                    ? `
                <p class="text-gray-700 text-xs ">${marker.description}</p> <!-- Reduced font size -->
            `
                    : ""
            }
            <!-- Address -->
            ${
                marker.address
                    ? `
                <p class="text-gray-600 text-xs ">
                    <i class="fas fa-map-marker-alt mr-1"></i> ${marker.address}
                </p>
            `
                    : ""
            }
            <!-- Price -->
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

// Function to add markers to both maps
async function addMarkersToMaps() {
    const markers = await fetchMarkers();

    markers.forEach((markerData) => {
        // Add marker to Leaflet map
        const leafletMarker = L.marker([
            markerData.latitude,
            markerData.longitude,
        ])
            .addTo(leafletMap)
            .bindPopup(createLeafletPopupContent(markerData), {
                maxWidth: 300,
                className: "custom-popup",
            });

        // Add marker to Google Maps
        const googleMarker = new google.maps.Marker({
            position: {
                lat: parseFloat(markerData.latitude),
                lng: parseFloat(markerData.longitude),
            },
            map: googleMap,
            title: markerData.name,
        });

        // Add info window to Google marker
        const infoWindow = new google.maps.InfoWindow({
            content: createGoogleMapsInfoContent(markerData),
            maxWidth: 300,
        });

        googleMarker.addListener("click", () => {
            infoWindow.open(googleMap, googleMarker);
        });
    });

    // If there are markers, center the maps on the first one
    if (markers.length > 0) {
        const firstMarker = markers[0];
        const position = {
            lat: parseFloat(firstMarker.latitude),
            lng: parseFloat(firstMarker.longitude),
        };

        leafletMap.setView([position.lat, position.lng], 12);
        googleMap.setCenter(position);
    }
}

// Initialize markers when the page loads
document.addEventListener("DOMContentLoaded", addMarkersToMaps);
