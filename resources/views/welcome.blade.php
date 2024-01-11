<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Drawing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://unpkg.com/fabric@latest/dist/fabric.js"></script>
    <script src="https://unpkg.com/fabric@latest/src/mixins/eraser_brush.mixin.js"></script>

    <style>
        canvas {
            border: 1px solid #000;
            cursor: crosshair;
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = new fabric.Canvas('drawingCanvas', {
                isDrawingMode: true,
                freeDrawingCursor: 'crosshair',
            });

            const handleZoom = (factor) => canvas.setZoom(canvas.getZoom() * factor);

            const createButton = (text, iconClass, clickHandler) => {
                const button = document.createElement('button');
                button.innerHTML = `<i class="${iconClass}"></i> ${text}`;
                button.addEventListener('click', clickHandler);
                document.body.appendChild(button);
                return button;
            };

            const toggleDrawingMode = () => {
                canvas.isDrawingMode = !canvas.isDrawingMode;
                if (canvas.isDrawingMode) {
                    canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
                    canvas.freeDrawingBrush.color = getById('lineColorInput').value;
                    canvas.freeDrawingBrush.width = parseInt(getById('lineWidthInput').value, 10) || 2;
                }
                drawingToolButton.innerHTML = `<i class="${canvas.isDrawingMode ? 'fas fa-pen' : 'fas fa-mouse-pointer'}"></i>`;
            };

            const toggleEraserMode = () => {
                canvas.isDrawingMode = !canvas.isDrawingMode;
                if (canvas.isDrawingMode) {
                    canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
                    canvas.freeDrawingBrush.color = '#fff';
                    canvas.freeDrawingBrush.width = 20;
                }
                drawingToolButton.innerHTML = `<i class="${canvas.isDrawingMode ? 'fas fa-eraser' : 'fas fa-mouse-pointer'}"></i>`;
            };

            const buttonsData = [
                { iconClass: 'fas fa-search-plus', handler: () => handleZoom(1.2) },
                { iconClass: 'fas fa-search-minus', handler: () => handleZoom(1 / 1.2) },
                {
                    iconClass: 'fas fa-undo',
                    handler: () => {
                        const objects = canvas.getObjects();
                        if (objects.length > 0) {
                            canvas.remove(objects[objects.length - 1]);
                            canvas.renderAll();
                        }
                    },
                },
                { iconClass: 'fas fa-eraser', handler: toggleEraserMode },
            ];

            const drawingToolButton = createButton('', 'fas fa-pen', toggleDrawingMode);
            drawingToolButton.style.left = '10px';
            drawingToolButton.style.bottom = '10px';
            document.body.appendChild(drawingToolButton);

            buttonsData.forEach((buttonData, index) => {
                const { iconClass, handler } = buttonData;
                const button = createButton('', iconClass, handler);
                button.style.left = `${70 + 60 * index}px`;
                button.style.bottom = '10px';
            });

            const getById = (id) => document.getElementById(id);

            const updateLineWidth = () => {
                const lineWidth = parseInt(getById('lineWidthInput').value, 10) || 2;
                canvas.freeDrawingBrush.width = canvas.isDrawingMode ? lineWidth : 20;
            };

            const updateLineColor = () => {
                canvas.freeDrawingBrush.color = getById('lineColorInput').value;
            };

            getById('lineWidthInput').addEventListener('input', updateLineWidth);
            getById('lineColorInput').addEventListener('input', updateLineColor);

            updateLineWidth();
            updateLineColor();
        });
    </script>
</body>

</html>
