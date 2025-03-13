document.addEventListener('DOMContentLoaded', function() {
    window.showRooms = function(buildingId) {
        // Fetch and display rooms for the selected building
        fetchRooms(buildingId);

    };

    window.showBuildings = function() {
        // Hide all room sections
        const allRooms = document.querySelectorAll('.rooms');
        allRooms.forEach(room => room.classList.add('hidden'));

        // Show the building selection again
        const buildingSection = document.querySelector('.building');
        if (buildingSection) {
            buildingSection.classList.remove('hidden');
        }
    };

    window.fetchRooms = function(buildingId) {

        fetch(`/buildings/${buildingId}`)
            .then(response => response.json())
            .then(data => {
                const roomsContainer = document.getElementById('rooms-container');
                roomsContainer.innerHTML = ''; // Clear previous rooms

                data.rooms.forEach(room => {
                    const roomDiv = document.createElement('div');
                    roomDiv.className = 'bg-white rounded-lg shadow-md overflow-hidden relative flex flex-col';
                    roomDiv.innerHTML = `
                        <img alt="${room.room_name}" class="w-full h-48 object-cover" src="${room.image || 'images/no-picture.jpg'}" />
                        <div class="p-4 flex-grow">
                            <h2 class="text-xl font-semibold text-gray-800">${room.room_name}</h2>
                            <p class="text-gray-600">${room.room_details}</p>
                            <div>Capacity: ${room.capacity} people</div>
                            <div>Price: ${room.service_rates} THB/night</div>
                        </div>
                    `;
                    roomsContainer.appendChild(roomDiv);
                });
            })
            .catch(error => console.error('Error fetching rooms:', error));
    };
});
