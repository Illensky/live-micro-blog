
const test = function() {
    console.log("hello worl");
}

setTimeout(() => {
    document.querySelectorAll('.alert').forEach(error => error.remove());
}, 3000);