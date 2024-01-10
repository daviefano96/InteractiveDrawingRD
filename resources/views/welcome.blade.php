<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Drawing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        canvas {
            border: 1px solid #000;
            cursor: default;
        }

        button {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3498db;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="m-4">
            <canvas id="drawingCanvas" width="800" height="600"></canvas>
        </div>
        <div class="mt-5">
            <div id="zoomIndicator">Zoom: 100% </div>
            <div class="mt-3">
                <input type="range" id="lineWidthInput" min="1" max="20" value="2">
                <input type="color" id="lineColorInput" value="#000000">
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/3.6.3/fabric.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fabricCanvas = new fabric.Canvas('drawingCanvas', {
                isDrawingMode: true,
                freeDrawingCursor: 'crosshair',
            });

            fabric.Object.prototype.transparentCorners = false;

            const getById = (id) => document.getElementById(id);

            const handleZoom = (factor) => {
                fabricCanvas.setZoom(fabricCanvas.getZoom() * factor);
                updateZoomIndicator();
            };

            const updateZoomIndicator = () => {
                const zoomPercentage = Math.round(fabricCanvas.getZoom() * 100);
                getById('zoomIndicator').innerText = `Zoom: ${zoomPercentage}%`;
            };

            const createButton = (text, iconClass, clickHandler) => {
                const button = document.createElement('button');
                button.innerHTML = `<i class="${iconClass}"></i> ${text}`;
                button.addEventListener('click', clickHandler);
                document.body.appendChild(button);
                return button;
            };

            const toggleDrawingMode = () => {
                fabricCanvas.isDrawingMode = !fabricCanvas.isDrawingMode;
                getById('drawingToolButton').innerHTML = `<i class="${fabricCanvas.isDrawingMode ? 'fas fa-pen' : 'fas fa-mouse-pointer'}"></i>`;
            };

            const buttonsData = [
                { iconClass: 'fas fa-search-plus', handler: () => handleZoom(1.2) },
                { iconClass: 'fas fa-search-minus', handler: () => handleZoom(1 / 1.2) },
            ];

            const drawingToolButton = createButton('', 'fas fa-pen', toggleDrawingMode);
            drawingToolButton.id = 'drawingToolButton'
            drawingToolButton.style.left = '10px';
            drawingToolButton.style.bottom = '10px';
            document.body.appendChild(drawingToolButton);


            buttonsData.forEach((buttonData, index) => {
                const { iconClass, handler } = buttonData;
                const button = createButton('', iconClass, handler);
                button.style.left = `${70 + 60 * index}px`;
                button.style.bottom = '10px';
            });

            const updateLineWidth = () => {
                fabricCanvas.freeDrawingBrush.width = parseInt(getById('lineWidthInput').value, 10) || 2;
            };

            const updateLineColor = () => {
                fabricCanvas.freeDrawingBrush.color = getById('lineColorInput').value;
            };

            getById('lineWidthInput').addEventListener('input', updateLineWidth);
            getById('lineColorInput').addEventListener('input', updateLineColor);

            updateLineWidth();
            updateLineColor();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
