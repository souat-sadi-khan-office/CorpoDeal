  // Disable right-click
  document.addEventListener("contextmenu", function(event) {
    event.preventDefault();
    show_toast("Right-click is disabled.");
});

// Disable specific keyboard shortcuts
document.addEventListener("keydown", function(event) {
    // F12
    if (event.keyCode === 123) {
        event.preventDefault();
        show_toast("Inspect Element is disabled.");
    }
    // Ctrl+Shift+I (Inspect Element), Ctrl+Shift+J (Console), Ctrl+U (View Source)
    if ((event.ctrlKey && event.shiftKey && (event.keyCode === 73 || event.keyCode === 74)) ||
        (event.ctrlKey && event.keyCode === 85)) {
        event.preventDefault();
        show_toast("Developer tools access is disabled.");
    }
    // Ctrl+S (Save), Ctrl+P (Print),Ctrl+A (Select All)
    if ((event.ctrlKey && (event.keyCode === 83 || event.keyCode === 80 || 
                           event.keyCode === 67 || event.keyCode === 65))) {
        event.preventDefault();
        show_toast("This action is disabled.");
    }
});

// // Disable text selection
// document.addEventListener("selectstart", function(event) {
//     event.preventDefault();
//     show_toast("Text selection is disabled.");
// });

// Disable drag
document.addEventListener("dragstart", function(event) {
    event.preventDefault();
    show_toast("Dragging is disabled.");
});

// Disable cut, copy, and paste actions
document.addEventListener("copy", function(event) {
    event.preventDefault();
    show_toast("Copy is disabled.");
});
document.addEventListener("cut", function(event) {
    event.preventDefault();
    show_toast("Cut is disabled.");
});
// document.addEventListener("paste", function(event) {
//     event.preventDefault();
//     show_toast("Paste is disabled.");
// });

// Function to show a toastDisable message
function show_toast(message) {
    // Create a toastDisable element if it doesn't exist
    if (!document.getElementById("toastDisable")) {
        const toast = document.createElement("div");
        toast.id = "toastDisable";
        toast.className = "toastDisable";
        document.body.appendChild(toast);
    }
    // Display the message in the toastDisable
    const toast = document.getElementById("toastDisable");
    toast.innerText = message;
    toast.classList.add("show");

    // Remove the message after 3 seconds
    setTimeout(function() {
        toast.classList.remove("show");
    }, 3000);
}
// setInterval(function() {
//     // Check if the developer tools are opened by comparing outer and inner dimensions
//     if (window.outerWidth - window.innerWidth > 100 || window.outerHeight - window.innerHeight > 100) {
//         alert("Developer tools are opened. Please close them to continue.");
//         window.location.href = '/'; // Redirect to the homepage or desired URL
//     }
// }, 1000);