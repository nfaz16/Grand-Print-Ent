# 3D T-Shirt Viewer

A web-based 3D t-shirt viewer built with Three.js that allows users to interactively view and rotate a 3D t-shirt model.

## Features

- **Interactive 3D Model**: View a detailed 3D t-shirt model
- **Mouse Controls**: Rotate the t-shirt horizontally by moving your mouse
- **Orbit Controls**: Use mouse to zoom and rotate the view
- **Responsive Design**: Adapts to different screen sizes
- **Modern UI**: Beautiful gradient background with glassmorphism effects

## Setup

1. **Add your 3D model**: Place your t-shirt GLTF model file in the `models/tshirt/` directory and name it `scene.gltf`

2. **Serve the files**: Since the application uses ES6 modules, you need to serve it through a web server. You can use:

   ```bash
   # Using Python (if available)
   python -m http.server 8000
   
   # Using Node.js (if available)
   npx http-server
   
   # Using PHP (if available)
   php -S localhost:8000
   ```

3. **Open in browser**: Navigate to `http://localhost:8000` (or the port your server is running on)

## File Structure

```
├── index.html          # Main HTML file
├── css/
│   └── style.css      # Additional styling
├── models/
│   └── tshirt/
│       └── scene.gltf # Your 3D t-shirt model (you need to add this)
└── README.md          # This file
```

## Controls

- **Mouse Movement**: Move your mouse left/right to rotate the t-shirt horizontally
- **Mouse Wheel**: Zoom in/out
- **Click and Drag**: Orbit around the model (restricted to horizontal plane)

## Technical Details

- Built with Three.js r129
- Uses GLTF format for 3D models
- Implements OrbitControls for camera movement
- Responsive canvas that adapts to window size
- Uses CDN imports for Three.js modules

## Model Requirements

Your GLTF model should be:
- Named `scene.gltf`
- Placed in the `models/tshirt/` directory
- Optimized for web (reasonable file size)
- Properly oriented (the code rotates it 180° to face forward)

## Customization

You can customize various aspects by modifying the JavaScript in `index.html`:

- **Model size**: Adjust the `object.scale.set()` values
- **Camera position**: Modify `camera.position.z` and `camera.position.set()`
- **Lighting**: Adjust the `DirectionalLight` and `AmbientLight` settings
- **Rotation sensitivity**: Modify the mouse rotation calculations

## Browser Compatibility

This application requires a modern browser with support for:
- ES6 modules
- WebGL
- Three.js

Tested on Chrome, Firefox, Safari, and Edge.