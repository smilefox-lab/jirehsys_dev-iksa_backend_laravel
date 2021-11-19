<script>
    "use strict";
    const properties = @json($properties);

    function initMap() {
        let markers = [];
        let infoWindows = [];

        const map = new google.maps.Map(document.getElementById('dashboard-map'), {
            center: { lat: -33.471193, lng: -70.667938 },
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
        });

        if (properties) {

            properties.forEach((property, i) => {
                const latLng = JSON.parse(property.coordinates);
                const marker = new google.maps.Marker({
                    position: latLng,
                    map,
                    title: `${property.id} - ${property.name}`
                });
                markers.push(marker);

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                    <div>
                        <h3>${property.id} - ${property.name}</h3>
                        <p>${property.description}</p>
                        <p>
                            <a href="${window.location.origin}/admin/real-estate/properties/edit/${property.id}">Ver</a></p>
                    </div>
                    `
                });
                infoWindows.push(infoWindow);
            });

            markers.forEach((marker, i) => marker.addListener("click", () => {
                if (infoWindows.length) {
                    infoWindows.forEach((infoWindow) => infoWindow.close());
                }
                infoWindows[i].open(map, marker);
            }));
        }

        map.addListener('click', async (mapsMouseEvent) => {
            if (infoWindows.length) {
                infoWindows.forEach((infoWindow) => infoWindow.close());
            }
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_map_api_key') }}&libraries=places&callback=initMap"
        async defer></script>
