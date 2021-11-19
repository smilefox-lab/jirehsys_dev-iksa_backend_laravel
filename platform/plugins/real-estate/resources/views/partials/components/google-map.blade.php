<script>
    "use strict";

    function initMap() {
        const coordinates = document.querySelector("input[name='coordinates']");
        let marker;
        let infoWindow;
        let myLatlng;
        
        if (coordinates && coordinates.value && Object.keys(JSON.parse(coordinates.value)).length) {
            myLatlng = JSON.parse(coordinates.value);
        } else {
            myLatlng = { lat: -33.471193, lng: -70.667938 };
        }

        const map = new google.maps.Map(document.getElementById('map'), {
            center: myLatlng,
            zoom: coordinates.value ? 15 : 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
        });

        if (coordinates.value) {
            marker = new google.maps.Marker({
                position: myLatlng
            });

            marker.setMap(map);
            map.setCenter(myLatlng);
        } else {
            // Create the initial InfoWindow.
            infoWindow = new google.maps.InfoWindow(
                {
                    content: 'Clic en el mapa para marcar la ubicación!',
                    position: myLatlng
                }
            );

            infoWindow.open(map);
        }

        // Configure the click listener.
        map.addListener('click', async (mapsMouseEvent) => {
            if (infoWindow) {
                // Close the current InfoWindow.
                infoWindow.close();
            }

            if (marker) {
                marker.setMap(null);
            }

            myLatlng = mapsMouseEvent.latLng;
            marker = new google.maps.Marker({
                position: myLatlng
            })

            marker.setMap(map);
            map.setCenter(myLatlng);

            coordinates.value = JSON.stringify(myLatlng);
            getAddress(myLatlng, map);
        });

        async function getAddress(location, map) {
            const { data } = await axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=0&lat=${location.lat()}&lon=${location.lng()}`);

            if (data.display_name) {
                const address = data.display_name;
                document.getElementById('location').value = address;
            }
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_map_api_key') }}&libraries=places&callback=initMap"
        async defer></script>
