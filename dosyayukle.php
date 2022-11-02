<html>

<head>
    <title>Dosya yükleme</title>
    <meta charset="utf-8">
</head>

<body>
        <?php
        $dizin = 'yuklenendosyalar/';
        $yuklenecek_dosya = $dizin . basename($_FILES['dosya']['name']);

        if (move_uploaded_file($_FILES['dosya']['tmp_name'], $yuklenecek_dosya)) {
            echo '

            <!DOCTYPE html>
            <html lang="en">
            
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="ie=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
                    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
                <title>AkarGuard Pdf Viewer</title>
            
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                    }
            
                    .top-bar {
                        background: #333;
                        color: #fff;
                        padding: 1rem;
                    }
            
                    .btn {
                        background: -webkit-linear-gradient(0deg, #139d8d, #37ed7d 100%);
                        ;
                        color: #fff;
                        border: none;
                        outline: none;
                        cursor: pointer;
                        padding: 0.7rem 2rem;
                    }
            
                    .btn:hover {
                        opacity: 0.9;
                    }
            
                    .page-info {
                        margin-left: 1rem;
                    }
            
                    .error {
                        background: -webkit-linear-gradient(0deg, #139d8d, #37ed7d 100%);
                        ;
                        color: #fff;
                        padding: 1rem;
                    }
            
                    input[type="file"] {
                        cursor: pointer !Important;
                        font: 300 14px sans-serif;
                        color: #9e9e9e;
                    }
            
                    input[type="file"]::-webkit-file-upload-button {
                        font: 300 14px sans-serif;
                        background: #009688;
                        border: 0;
                        padding: 12px 25px;
                        cursor: pointer;
                        color: #fff;
                        text-transform: uppercase;
                    }
            
                    input[type="file"]::-ms-browse {
                        font: 300 14px "Roboto", sans-serif;
                        background: #009688;
                        border: 0;
                        padding: 12px 25px;
                        cursor: pointer;
                        color: #fff;
                        text-transform: uppercase;
                    }
            
                    #sendfile {
                        font: 300 14px sans-serif;
                        background: #009688;
                        border: 0;
                        padding: 12px 25px;
                        cursor: pointer;
                        color: #fff;
                        text-transform: uppercase;
                    }

                    #pdf-render {
                        width: 800px;
                        height: 600px;
                        max-width: 800px;
                        max-height: 600px;
                    }
                </style>
            </head>
            
            <body>
            
                <!-- <div id="loader">
                    
                    <input type="file" name="filee" id="filee">
                    <button type="button" id="sendfile" onclick="upload()">Exec</button>
            
                </div>
            
                <div style="display:none;" id="myDiv"> -->
                    <div class="top-bar">
                        <button class="btn" id="prev-page">
                            <i class="fas fa-arrow-circle-left"></i> Prev Page
                        </button>
                        <button class="btn" id="next-page">
                            Next Page <i class="fas fa-arrow-circle-right"></i>
                        </button>
                        <span class="page-info">
                            Page <span id="page-num"></span> of <span id="page-count"></span>
                        </span>
                    </div>
            
                    <canvas id="pdf-render"></canvas>
                <!-- </div> -->
                <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
            </body>
            
            </html>
            
            <script>
                // function upload() {
                    // var doc = document.getElementById("filee").value;
                    // var url = doc;
                    // yol = url.replace("C:\\fakepath\\", "");
                    url = "./'.$yuklenecek_dosya.'";
            
                    let pdfDoc = null,
                        pageNum = 1,
                        pageIsRendering = false,
                        pageNumIsPending = null;
            
                    const scale = 1.5,
                        canvas = document.querySelector("#pdf-render"),
                        ctx = canvas.getContext("2d");
            
                    const renderPage = num => {
                        pageIsRendering = true;
            
                        pdfDoc.getPage(num).then(page => {
                            const viewport = page.getViewport({ scale });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
            
                            const renderCtx = {
                                canvasContext: ctx,
                                viewport
                            };
            
                            page.render(renderCtx).promise.then(() => {
                                pageIsRendering = false;
            
                                if (pageNumIsPending !== null) {
                                    renderPage(pageNumIsPending);
                                    pageNumIsPending = null;
                                }
                            });
            
                            document.querySelector("#page-num").textContent = num;
                        });
                    };
            
                    const queueRenderPage = num => {
                        if (pageIsRendering) {
                            pageNumIsPending = num;
                        } else {
                            renderPage(num);
                        }
                    };
            
                    const showPrevPage = () => {
                        if (pageNum <= 1) {
                            return;
                        }
                        pageNum--;
                        queueRenderPage(pageNum);
                    };
            
                    const showNextPage = () => {
                        if (pageNum >= pdfDoc.numPages) {
                            return;
                        }
                        pageNum++;
                        queueRenderPage(pageNum);
                    };
            
                    pdfjsLib
                        .getDocument(url)
                        .promise.then(pdfDoc_ => {
                            pdfDoc = pdfDoc_;
                            document.querySelector("#page-count").textContent = pdfDoc.numPages;
            
                            renderPage(pageNum);
                        })
                        .catch(err => {
                            const div = document.createElement("div");
                            div.className = "error";
                            div.appendChild(document.createTextNode(err.message));
                            document.querySelector("body").insertBefore(div, canvas);
                            document.querySelector(".top-bar").style.display = "none";
                        });
            
                    document.querySelector("#prev-page").addEventListener("click", showPrevPage);
                    document.querySelector("#next-page").addEventListener("click", showNextPage);
            
            
                //     showPage();
                // }
            
                // function showPage() {
                //     document.getElementById("loader").style.display = "none";
                //     document.getElementById("myDiv").style.display = "block";
                // }
            
            
            </script>
            ';
        } else {
            echo "Dosya yüklenemedi!\n";
        }
        ?>
</body>

</html>