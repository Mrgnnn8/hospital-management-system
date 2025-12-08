document.addEventListener('DOMContentLoaded', function() {
    
    const form = document.getElementById('doctorForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            const staffInput = document.getElementById('staffno');
            const staffValue = staffInput.value.trim();

            if (staffValue.length !== 5) {
                event.preventDefault(); 
                
                alert("Validation Error: Staff ID Number must be exactly 5 characters long (e.g., MJ001).");
                
                staffInput.style.border = "2px solid red";
                staffInput.focus();
            }
        });
        
        const staffInput = document.getElementById('staffno');
        staffInput.addEventListener('input', function() {
            if (this.value.length === 5) {
                this.style.border = "1px solid #ccc";
            }
        });
    }
});