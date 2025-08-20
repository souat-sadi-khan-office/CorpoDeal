<div class="uploadOuter mb-3">
    <small class="text-danger">Image size will be 135 X 135 Pixel</small> <br>
    <label for="uploadFile" class="btn btn-light form-label">Upload Image</label>
    <strong>OR</strong>
    <span class="dragBox">
        Drag and Drop image here
        <input type="file" name="photo" id="uploadFile" />
    </span>
</div>

@push('script')
    <script>
         function dragNdrop(event) {
            var fileName = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("preview");
            var previewImg = document.createElement("img");
            previewImg.setAttribute("src", fileName);
            preview.innerHTML = "";
            preview.appendChild(previewImg);
        }

        function drag() {
            document.getElementById('uploadFile').parentNode.className = 'draging dragBox';
        }

        function drop() {
            document.getElementById('uploadFile').parentNode.className = 'dragBox';
        }
    </script>
@endpush
@push('style')
    <style>
        .uploadOuter {
            text-align: center;
            padding: 20px;

            strong {
                padding: 0 10px
            }
        }

        .dragBox {
            width: 250px;
            height: 100px;
            margin: 0 auto;
            position: relative;
            text-align: center;
            font-weight: bold;
            line-height: 95px;
            color: #999;
            border: 2px dashed #ccc;
            display: inline-block;
            transition: transform 0.3s;

            input[type="file"] {
                position: absolute;
                height: 100%;
                width: 100%;
                opacity: 0;
                top: 0;
                left: 0;
            }
        }

        .draging {
            transform: scale(1.1);
        }

        #preview {
            text-align: center;

            img {
                max-width: 250px!important
            }
        }
    </style>
@endpush