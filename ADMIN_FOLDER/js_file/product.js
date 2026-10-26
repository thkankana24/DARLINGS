function openProductPopup() {
    document.getElementById("productPopup").style.display = "flex";
}

function closeProductPopup() {
    document.getElementById("productPopup").style.display = "none";
}

/* Close when clicking outside popup */
document.getElementById("productPopup").addEventListener("click", function(e) {

    if (e.target === this) {
        closeProductPopup();
    }

});


