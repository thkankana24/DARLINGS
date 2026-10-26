
        document.getElementById("imageUpload").addEventListener("change", function (event) {

            const file = event.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function (e) {

                    const imagePreview = document.getElementById("imagePreview");
                    const uploadContent = document.getElementById("uploadContent");

                    imagePreview.src = e.target.result;

                    imagePreview.style.display = "block";
                    uploadContent.style.display = "none";

                };

                reader.readAsDataURL(file);
            }

        });

