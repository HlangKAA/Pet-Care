var detailpanel = document.getElementById("guestdetailpanel");

adduseropen = () => {
    detailpanel.style.display = "flex";
}
adduserclose = () => {
    detailpanel.style.display = "none";
}

//search bar logic using js
const searchFun = () =>{
    let filter = document.getElementById('search_bar').value.toUpperCase();

    let myTable = document.getElementById("table-data");

    let tr = myTable.getElementsByTagName('tr');

    for(var i = 0; i< tr.length;i++){
        let td = tr[i].getElementsByTagName('td')[1];

        if(td){
            let textvalue = td.textContent || td.innerHTML;

            if(textvalue.toUpperCase().indexOf(filter) > -1){
                tr[i].style.display = "";
            }else{
                tr[i].style.display = "none";
            }
        }
    }

}

// Function to update room options based on selected animal type
function updateRoomOptions() {
    const animalSelect = document.getElementById('animalSelect');
    const roomSelect = document.getElementById('roomSelect');
    const selectedAnimal = animalSelect.value;

    // Clear existing options
    roomSelect.innerHTML = '<option value selected>เลือกประเภทห้อง</option>';

    if (selectedAnimal === 'แมว') {
        roomSelect.innerHTML += `
            <option value="ห้องเล็ก - แมว">ห้องเล็ก - แมว</option>
            <option value="ห้องใหญ่ - แมว">ห้องใหญ่ - แมว</option>
        `;
        roomSelect.disabled = false;
    } else if (selectedAnimal === 'หมา') {
        roomSelect.innerHTML += `
            <option value="ห้องเล็ก - หมา">ห้องเล็ก - หมา</option>
            <option value="ห้องใหญ่ - หมา">ห้องใหญ่ - หมา</option>
        `;
        roomSelect.disabled = false;
    } else {
        roomSelect.disabled = true;
    }
}

// Validate dates to ensure check-out is after check-in
function validateDates() {
    const cinInput = document.querySelector('input[name="cin"]');
    const coutInput = document.querySelector('input[name="cout"]');
    
    if (cinInput && coutInput) {
        cinInput.addEventListener('change', function() {
            // Set minimum date for checkout to be the day after check-in
            const cinDate = new Date(this.value);
            const nextDay = new Date(cinDate);
            nextDay.setDate(nextDay.getDate() + 1);
            
            const formatDate = nextDay.toISOString().split('T')[0];
            coutInput.min = formatDate;
            
            // If current checkout date is before new check-in date, reset it
            if (new Date(coutInput.value) <= cinDate) {
                coutInput.value = formatDate;
            }
        });
    }
}

// Initialize event listeners when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum check-in date to today
    const today = new Date().toISOString().split('T')[0];
    const cinInput = document.querySelector('input[name="cin"]');
    if (cinInput) {
        cinInput.min = today;
    }
    
    // Initialize date validation
    validateDates();
});
