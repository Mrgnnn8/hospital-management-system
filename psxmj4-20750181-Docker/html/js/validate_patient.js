document.addEventListener('DOMContentLoaded', function() {
    
    const form = document.getElementById('patientForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            const nhsInput = document.getElementById('nhs_no');
            const nhsValue = nhsInput.value.trim();

            if (nhsValue.length !== 6) {
                event.preventDefault(); 
                
                alert("Validation Error: NHS Number must be exactly 6 characters long.");
                
                nhsInput.style.border = "2px solid red";
                nhsInput.focus();
            }
        });
    }
});