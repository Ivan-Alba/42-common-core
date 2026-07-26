# FdF — Fil de Fer (3D Wireframe Viewer)

<p align="center">
  <img src="README_assets/demo.gif" alt="FdF Demo" width="100%" />
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Project-FdF-000000?style=for-the-badge&logo=42&logoColor=white" alt="FdF" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Graphics-MiniLibX-orange?style=for-the-badge" alt="MiniLibX" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview
**FdF (Fil de Fer)** is a graphical 3D wireframe renderer developed in C. The program processes topographical landscape maps represented as two-dimensional coordinate grids with elevation values ($Z$) and converts them into interactive 3D graphical representations using trigonometric projection models.

Built using **MiniLibX** (the 42 School internal graphics library), this implementation features real-time interactive controls, dynamic camera positioning, dual projection rendering (Isometric and Orthographic), horizontal 3D mesh rotation, custom altitude modification, and custom hexadecimal color rendering.

---

## 📋 Technical Specifications & Key Features

*   **Projection Engines**: Dynamic switching between **Isometric Projection** (30° default tilt) and top-down **Orthographic View** using isometric coordinate matrix transformations.
*   **3D Mesh Manipulation**: Full horizontal rotation on the $X-Y$ plane around the map's geometric center, real-time zoom scaling proportional to map dimensions, and $Z$-altitude scaling.
*   **Bounding-Box Camera Containment**: Intelligent translation algorithms (panning) with dynamic speed steps and boundary checks to prevent losing visual tracking of the map.
*   **Color Parsing**: Native support for explicit hexadecimal color codes embedded in target map files (`0xFF0000`), with fallback elevation-based color gradients.
*   **Performance Optimization**: Direct pixel buffering rendered via MiniLibX image buffer pointers before pushing frames to the display canvas to eliminate flickering.

---

## 💻 MiniLibX & Subsystem Dependencies

This project relies on **MiniLibX**, an X11/AppKit wrapper library for low-level window management, pixel drawing, and event handling.

### System Requirements (Linux)

To compile and execute MiniLibX on Linux systems, the following X11 library dependencies are required:

```bash
sudo apt-get update
sudo apt-get install build-essential libx11-dev libxext-dev zlib1g-dev
```

---

## 🎮 Controls & Interface

| Key Binding | Action / Function |
| :--- | :--- |
| `W` / `A` / `S` / `D` | Translate / Pan camera view |
| `+` / `-` or Scroll | Dynamic Zoom In / Zoom Out |
| `Q` / `E` | Rotate map horizontally around central axis |
| `Z` / `X` | Increase / Decrease $Z$-altitude elevation |
| `SPACE` | Toggle between **Isometric** and **Orthographic** projections |
| `ESC` / `[X]` Click | Cleanly close window, free memory resources, and exit |

---

## 🛠️ Project Architecture

```text
.
├── fdf.h
├── main.c
├── map_info.c
├── read_map.c
├── event_controller.c
├── menu.c
├── print_pixels.c
├── utils.c
├── exit.c
└── Makefile
```

*   **fdf.h**: Central header file containing structural definitions (`t_vars`, `t_map`, `t_points`), macros, library inclusions, and global function prototypes.
*   **main.c**: Entry point initializing environment configuration, map allocation routines (`initialize_map_info`), event hooks (`mlx_hook`), and primary execution loops.
*   **map_info.c**: Core trigonometric conversion pipeline (`get_iso_values`), initial map coordinate generation (`set_points_values`), and auto-centering calculation (`center_render`).
*   **render.c**: Bresenham line-drawing algorithms, image pixel pushing routines, and window buffer refresh pipelines (`refresh_render`).
*   **event_controller.c**: Key event dispatchers handling horizontal rotation (`rotate_horizontal`), scale adjustments (`change_scale`), projection toggle (`toggle_projection`), and altitude scaling (`modify_z`).
*   **menu.c**: On-screen dynamic HUD interface rendering live metrics (camera position, angle, and relative zoom percentage).
*   **utils.c**: General helper functions including hexadecimal color parsers (`hex_to_int`), integer-to-string formatters, and global bounding box calculation helpers (`get_map_bounds`).
*   **exit.c**: Program termination procedures handling window destruction, MiniLibX resource clearing, and dynamic map memory deallocation (`close_win`).

---

## 🚀 Compilation & Usage

### Compilation

The project includes a robust `Makefile` supporting standard targets. Compile the executable by running:

```bash
make
```

Additional `Makefile` rules:
*   `make clean`: Removes intermediate object files (`.o`).
*   `make fclean`: Removes object files and the final `fdf` binary executable.
*   `make re`: Recompiles the entire project from scratch.

### Program Execution

Pass any valid `.fdf` map file path as an argument to the compiled binary:

```bash
./fdf maps/42.fdf
```

To test with different terrain topologies provided in standard test suites:

```bash
./fdf maps/pyramide.fdf
./fdf maps/mars.fdf
```

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
