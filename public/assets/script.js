// Initialize both maps
let leafletMap = L.map('leaflet-map').setView([-8.371760437036368, 115.19692080521014], 9);
let googleMap = new google.maps.Map(document.getElementById('google-map'), {
    center: { lat: -8.371760437036368, lng: 115.19692080521014 },
    zoom: 9
});

// Add Leaflet tile layer (OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(leafletMap);

// Function to fetch markers from the database
async function fetchMarkers() {
    try {
        const response = await fetch('/api/markers'); // Ensure API route is properly defined in Laravel
        if (!response.ok) {
            throw new Error('Failed to fetch markers.');
        }
        const markers = await response.json();
        return markers;
    } catch (error) {
        console.error('Error fetching markers:', error);
        return [];
    }
}

// Functions to create popup content for Leaflet and Google Maps
function createLeafletPopupContent(marker) {
    return `
        <div class="flex flex-col md:flex-row max-w-md rounded overflow-hidden shadow-lg bg-white">
            <!-- Gambar -->
            ${marker.image ? `
                <img src="/storage/${marker.image}" alt="${marker.name}"
                     class="w-full md:w-32 h-32 object-cover rounded-t md:rounded-l md:rounded-t-none">
            ` : ''}
            <!-- Konten -->
            <div class="p-4 flex-1">
                <!-- Nama Marker -->
                <div class="font-bold text-lg mb-2 text-gray-800">${marker.name}</div>
                <!-- Deskripsi -->
                ${marker.description ? `
                    <p class="text-gray-700 text-sm mb-2">${marker.description}</p>
                ` : ''}
                <!-- Alamat -->
                ${marker.address ? `
                    <p class="text-gray-600 text-xs mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i> ${marker.address}
                    </p>
                ` : ''}
                <!-- Harga -->
                ${marker.price ? `
                    <p class="text-green-600 font-semibold mb-1">
                        <i class="fas fa-tag mr-1"></i> $${marker.price}
                    </p>
                ` : ''}
                <!-- Rating -->
                ${marker.rate ? `
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                        <span class="text-gray-700">${marker.rate}/5</span>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}


function createGoogleMapsInfoContent(marker) {
    return `
        <div style="display: flex; max-width: 350px; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);">
            ${marker.image ? `
                <img src="/storage/${marker.image}" alt="${marker.name}"
                     style="width: 120px; height: 120px; object-fit: cover; flex-shrink: 0;">
            ` : ''}
            <div style="padding: 12px; flex: 1;">
                <div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">
                    ${marker.name}
                </div>
                ${marker.description ? `
                    <p style="color: #4a5568; font-size: 14px; margin-bottom: 8px;">
                        ${marker.description}
                    </p>
                ` : ''}
                ${marker.address ? `
                    <p style="color: #718096; font-size: 12px; margin-bottom: 8px;">
                        <i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i>
                        ${marker.address}
                    </p>
                ` : ''}
                ${marker.price ? `
                    <p style="color: #059669; font-weight: 600; margin-bottom: 4px;">
                        <i class="fas fa-tag" style="margin-right: 4px;"></i>
                        $${marker.price}
                    </p>
                ` : ''}
                ${marker.rate ? `
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-star" style="color: #FBBF24; margin-right: 4px;"></i>
                        <span style="color: #4a5568;">${marker.rate}/5</span>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}


// Function to add markers to both maps
async function addMarkersToMaps() {
    const markers = await fetchMarkers();

    markers.forEach(marker => {
        // Add marker to Leaflet map
        const leafletMarker = L.marker([marker.latitude, marker.longitude])
            .addTo(leafletMap)
            .bindPopup(createLeafletPopupContent(marker));

        // Add marker to Google Maps
        const googleMarker = new google.maps.Marker({
            position: { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) },
            map: googleMap,
            title: marker.name
        });

        const infoWindow = new google.maps.InfoWindow({
            content: createGoogleMapsInfoContent(marker)
        });

        googleMarker.addListener('click', () => {
            infoWindow.open(googleMap, googleMarker);
        });
    });

    // Center maps on the first marker
    if (markers.length > 0) {
        const firstMarker = markers[0];
        leafletMap.setView([firstMarker.latitude, firstMarker.longitude], 12);
        googleMap.setCenter({ lat: parseFloat(firstMarker.latitude), lng: parseFloat(firstMarker.longitude) });
    }
}

// Load markers when the page loads
document.addEventListener('DOMContentLoaded', addMarkersToMaps);
