# T-Shirt 3D Model

Place your t-shirt 3D model file here.

## Required File

- **Filename**: `scene.gltf`
- **Format**: GLTF (GL Transmission Format)
- **Location**: This directory (`models/tshirt/`)

## Model Guidelines

1. **File Format**: Use GLTF (.gltf) format for best compatibility
2. **Size**: Keep the file size reasonable for web loading (under 10MB recommended)
3. **Orientation**: The model will be rotated 180° in the code to face forward
4. **Scale**: The model will be scaled to 0.07x in all dimensions
5. **Materials**: Ensure materials are embedded or referenced correctly

## Where to Get Models

You can find 3D t-shirt models from:
- [Sketchfab](https://sketchfab.com) (search for "t-shirt" or "shirt")
- [TurboSquid](https://www.turbosquid.com)
- [CGTrader](https://www.cgtrader.com)
- Create your own using Blender, Maya, or other 3D software

## Converting Models

If you have a model in a different format (FBX, OBJ, etc.), you can convert it to GLTF using:
- [Blender](https://www.blender.org) (free, has GLTF export)
- [glTF-Transform](https://gltf-transform.donmccurdy.com) (online converter)
- [FBX2glTF](https://github.com/facebookincubator/FBX2glTF) (command-line tool)

Once you have your `scene.gltf` file, place it in this directory and the viewer will automatically load it.