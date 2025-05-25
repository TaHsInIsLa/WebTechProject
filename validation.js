function validateForm() {
    const name = document.getElementById("fullname").value.trim();
    const gender = document.querySelector('input[name="gender"]:checked');
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    const dob = document.getElementById("dob").value;
    const country = document.getElementById("country").value;
    const opinion = document.getElementById("opinion").value.trim();
    const terms = document.getElementById("terms").checked;
  
    const nameRegex = /^[a-zA-Z.,\- ]+$/;

if (name.length <= 5) {
  alert("Full Name must be more than 5 characters.");
  return false;
}

if (!nameRegex.test(name)) {
  alert("Full Name can only contain letters, spaces, periods (.), commas (,), and hyphens (-). Numbers are not allowed.");
  return false;
}
  
    if (!gender) {
      alert("Please select your gender.");
      return false;
    }
  
    if (!/^[a-zA-Z0-9._%+-]+@(gmail|yahoo)\.com$/.test(email)) {
      alert("Email must be a valid Gmail or Yahoo address ending with .com");
      return false;
    }
  
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
    if (!passwordRegex.test(password)) {
      alert("Password must be at least 8 characters and include 1 uppercase letter, 1 number, and 1 special character.");
      return false;
    }
  
    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      return false;
    }
  
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    if (isNaN(age) || age < 18) {
      alert("You must be at least 18 years old.");
      return false;
    }
  
    if (country === "") {
      alert("Please select a country.");
      return false;
    }
  
    if (opinion === "") {
      alert("Please share your opinion.");
      return false;
    }
  
    if (!terms) {
      alert("You must accept the Terms and Conditions.");
      return false;
    }
  
    alert("Form submitted successfully!");
    return true;
  }
  function validateLogin() {
  const email = document.getElementById("login-email").value.trim();
  const password = document.getElementById("login-password").value;

  if (email === "") {
    alert("Please enter your email.");
    return false;
  }

  if (password === "") {
    alert("Please enter your password.");
    return false;
  }


  return true; 
}