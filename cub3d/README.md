# cub3D — Raycasting Graphics Engine from Scratch (Four Seasons Adventures)

<p align="center">
  <img src="https://img.shields.io/badge/Project-cub3D-000000?style=for-the-badge&logo=42&logoColor=white" alt="cub3D" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Graphics-Raycasting%20%7C%20MinilibX-FF6F00?style=for-the-badge" alt="Raycasting" />
  <img src="https://img.shields.io/badge/Audio-BASS%20Library-0071C5?style=for-the-badge" alt="BASS Audio" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

<table align="center">
  <tr>
    <td align="center" width="50%">
      <img src="./README_assets/title.gif" width="100%" alt="Four Seasons Adventures Title Menu" />
      <i>Game Title Menu (Concept Art & UI State Machine)</i>
    </td>
    <td align="center" width="50%">
      <img src="./README_assets/winter-end.gif" width="100%" alt="Winter Season Gameplay and Win State" />
      <i>Gameplay Loop (Winter Campaign & Win Condition)</i>
    </td>
  </tr>
</table>

---

## 📖 Overview
**cub3D** is a dynamic 3D raycasting graphics engine built from scratch in C, heavily inspired by the foundational mechanics of *Wolfenstein 3D* (1992). The engine translates 2D grid matrix configurations into a fully interactive, textured first-person 3D perspective using Digital Differential Analysis (DDA) algorithms and low-level pixel manipulation via MiniLibX.

This extended implementation, titled **Four Seasons Adventures**, evolves the core specification into a full-fledged retro game engine. It features dynamic level chaining based on Vivaldi's Four Seasons, distance-based software depth shading, 2D textured horizontal plane rendering (floors & ceilings), procedural sliding doors, distance-sorted 2D billboard sprites, custom HUD overlays, and multi-threaded 8-bit chiptune audio via the BASS library.

---

## 📋 Technical Specifications & Key Features

*   **DDA Raycasting & Perspective Correction**: High-performance raycasting engine projecting 1D slice samples into 3D vertical wall segments. Fish-eye perspective distortion is corrected dynamically using angular trigonometric offsets ($\cos(\Delta\theta)$).
*   **Dynamic Map Configuration Linker**: Custom `.cub` parser capable of reading a `NEXT_MAP` attribute to dynamically chain level transitions, seamlessly unloading and loading new map states without memory leaks.
*   **Horizontal Floor & Ceiling Texturing**: 2D plane projection mapping horizontal textures relative to the player's spatial orientation vector and field of view.
*   **Atmospheric Distance Depth Shading**: Software-calculated distance shading engine that dynamically blends texture RGB values toward black based on ray impact distance.
*   **Interactive Entities & Procedural Sliding Doors**: Dynamic wall collision detection for sliding doors with fractional offsets, alongside distance-sorted 2D billboard sprites (keys, coins) with clip-prevention depth ordering.
*   **Multi-Threaded Audio Infrastructure**: Native integration of the **BASS Audio Library**, executing synchronized 8-bit chiptune arrangements of Vivaldi's Four Seasons and event-triggered audio FX (doors, collectibles, portal teleports).
*   **Real-Time Vector Minimap & Retro HUD**: Top-right vector minimap displaying player direction and field of view cone, accompanied by a retro alphanumeric HUD blitting scores and status icons onto the frame buffer.

---

## 🛠️ Project Architecture

```text
.
├── Makefile                        # Compilation rules with MLX and BASS library flags
├── assets/                         # XPM textures, sprites, UI elements, maps, and audio tracks
│   ├── UI/                         # HUD status icons and retro alphanumeric font sprites
│   ├── audio/                      # Chiptune sound tracks and sound effect files
│   ├── maps/                       # Campaign .cub maps (Spring, Summer, Autumn, Winter)
│   ├── sprites/                    # Billboard collectible textures (coins, keys, portal FX)
│   ├── textures/                   # Wall, floor, ceiling, and door XPM textures
│   └── title_screen/               # Splash screen and menu graphics
│
├── bass/                           # BASS Audio Library integration and header definitions
├── minilibx-linux/                 # Low-level X11/Wayland graphical windowing framework
│
├── inc/                            # System headers
│   ├── cub3d.h                     # Master structure definitions, player state, and rendering contexts
│   ├── raycast.h                   # DDA algorithm prototypes, vector math, and shading functions
│   ├── minimap.h                   # Vector minimap overlay calculation headers
│   ├── sprite.h                    # Billboard sprite sorting and distance projection headers
│   └── audio.h                     # BASS library sound wrapper prototypes
│
├── src/                            # Modular engine implementation
│   ├── main/                       # Program entry point and top-level game loop orchestrator
│   ├── file/                       # Strict .cub map parsing, validation, and dynamic level chain linker
│   ├── game/                       # Player state machine, collision box detection, and movement
│   ├── raycast/                    # DDA ray calculation, wall intersection, and fish-eye correction
│   ├── render/                     # Direct frame buffer manipulation, depth shading, floor/ceiling mapping
│   ├── mlx/                        # MLX window hooks, keyboard/mouse event listeners, and exit routines
│   ├── audio/                      # Multi-threaded BASS audio soundscape manager
│   └── utils/                      # Vector arithmetic, memory release, and error handling routines
│
└── README_assets/                  # Gameplay animations and graphical showcases for documentation
```

## 🧠 Raycasting Engine: Mathematics & Render Pipeline

The core rendering engine iterates through every vertical screen column $x \in [0, \text{WIDTH})$, sweeping the ray angle $\alpha$ across the player's Field of View (FOV):

$$\alpha = \text{normalize}\left(\theta_{\text{player}} + \frac{\text{FOV}}{2} - x \cdot \Delta\alpha\right), \quad \text{where } \Delta\alpha = \frac{\text{FOV}}{\text{WIDTH}}$$

```text
                      Ray Angle α = θ + FOV/2 - x·Δα
                               \  |  /
                                \ | /  FOV
                                 \|/
                          [ Player Vector θ ]
```

### 1. Euclidean Distance & Corner-Case Disambiguation
For each column, DDA calculates horizontal ($d_{\text{horz}}$) and vertical ($d_{\text{vert}}$) ray intersection points relative to the player's spatial position $(P_x, P_y)$:

$$d = \sqrt{(P_x - \text{hit}_x)^2 + (P_y - \text{hit}_y)^2}$$

When a ray hits a grid intersection at an exact corner ($|d_{\text{horz}} - d_{\text{vert}}| < \epsilon$), standard raycasters exhibit visual flickering or wall clipping. The engine solves this by analyzing the ray quadrant ($\alpha$) to adjust grid tile offsets and resolve collision precedence deterministically:

$$W_{\text{type}} = \text{Tile}(H) \quad \text{if } W_{\text{vert}} = \text{WALL}$$

$$W_{\text{type}} = \text{Tile}(V) \quad \text{otherwise}$$


### 2. Fish-Eye Correction & Wall Height Projection
To prevent planar distortion ("fish-eye effect"), the raw Euclidean distance $d_{\text{raw}}$ is converted into a perpendicular distance relative to the player's viewing direction:

$$\beta = \text{normalize}(\alpha - \theta_{\text{player}})$$

$$d_{\text{corrected}} = d_{\text{raw}} \cdot \cos(\beta)$$

Using the distance to the projection plane ($d_{\text{pp}}$) and grid unit size, the projected wall height in pixels is calculated as:

$$H_{\text{wall}} = \left\lceil \frac{S \cdot d_{\text{pp}}}{d} \right\rceil$$

### 3. Depth Shading & Viewport Layout
To simulate realistic environmental illumination, every pixel's color value is calculated as a function of Euclidean distance:

$$I_{\text{pixel}} = I_{\text{base}} \times \max\left(0, 1 - \frac{d}{d_{\max}}\right)$$

Screen coordinates are offset vertically to reserve space for the retro UI overlay (`UI_SIZE`):

$$Y_{\text{wall}} = \frac{H \cdot (1 + U)}{2} - \frac{H_{\text{wall}}}{2} - 1$$

```text
       +-----------------------------------------+ --- y = 0
       |               RETRO UI / HUD            |
       +-----------------------------------------+ --- y = HEIGHT * UI_SIZE
       |                                         |
       |                 CEILING                 |
       |                                         |
       |============= WALL / DOOR ===============| --- y = wall_y
       |                                         |
       |                 TEXTURE                 |
       |                                         |
       |=========================================| --- y = wall_y + wall_height
       |                                         |
       |                  FLOOR                  |
       +-----------------------------------------+ --- y = HEIGHT - 1
```

---

## 🎮 Game Showcase & Feature Mechanics

<table align="center">
  <tr>
    <td align="center" width="50%">
      <img src="./README_assets/map-transition.gif" width="100%" alt="Dynamic Map Linker and Season Transition" />
      <i>Campaign Transitions & Dynamic Map Linker</i>
    </td>
    <td align="center" width="50%">
      <img src="./README_assets/autumn.gif" width="100%" alt="Autumn Level Mechanics and Dynamic Lighting" />
      <i>Autumn Level (Depth Shading & Billboard Sprites)</i>
    </td>
  </tr>
</table>

### Dynamic Map Linker & Vivaldi Campaign
Rather than compiling static level layouts, the parser parses a custom `NEXT_MAP` key inside `.cub` files. Stepping into a portal triggers a full hot-unload of the active spatial grid, releases current textures, and immediately initializes the next seasonal stage (*Spring* $\rightarrow$ *Summer* $\rightarrow$ *Autumn* $\rightarrow$ *Winter*).

---

## 💻 `.cub` Configuration File Format

The parser strictly validates `.cub` configuration files prior to game initialization, ensuring texture files exist, walls are fully enclosed, and a valid path to the objective exists.

### Mandatory Format
Includes strict cardinal wall textures (or RGB color values for floor/ceiling) and the initial player spawn direction (`N`, `S`, `E`, or `W`):

```text
NO ./assets/textures/spring_2.xpm
SO ./assets/textures/spring_2.xpm
WE ./assets/textures/spring_2_dark.xpm
EA ./assets/textures/spring_2_dark.xpm
F ./assets/textures/floor/spring.xpm
C ./assets/textures/ceil/spring.xpm

        1111111111111111111111111
        1000000001000100000000001
       11011000001000100000000001
      100000000001101100000000W01
111111111011010000000000000000001
100000000011000000010110111111111
111111111011111011010110010001
11110111111111011101011011001
11000000110101011101000010001
10000000000000001101010010001
10000000000000000001000000001
11000001110101011101011110111
11110111 1110101 111111000001
11111111 1111111 111111111111
```

### Extended Bonus Format
Supports interactive entities, procedural sliding doors, keys, collectibles, environmental decorations, and level-chaining metadata (`NEXT` map linking):

*   **Identifiers**: `D` (Door), `L` (Locked Door), `EXIT` (Portal Sprite), `TREE` (Decoration Sprite), `NEXT` (Path to next campaign map).
*   **Grid Tokens**: `K` (Key), `C` (Coin), `Q` (Chest), `D` (Door), `L` (Locked Door), `T` (Tree), `X` (Exit Portal).

```text
NO ./assets/textures/spring_2.xpm
SO ./assets/textures/spring_2.xpm
WE ./assets/textures/spring_2_dark.xpm
EA ./assets/textures/spring_2_dark.xpm
F ./assets/textures/floor/spring.xpm
C ./assets/textures/ceil/spring.xpm
D ./assets/textures/springdoor.xpm
L ./assets/textures/springdoor_locked.xpm
EXIT ./assets/sprites/spring_portal.xpm
TREE ./assets/sprites/spring_tree.xpm
NEXT ./assets/maps/4seasons/map2.cub

        1111111111111111111111111
        1Q000000C10Q0100000C00001
       1101100T001000100T00000001
      10000D0000011C1100000000W01
111111111011010000000000000000001
1X000L000C1100000001D11D111111111
111111111011111011010110Q100Q1
1111C111111111011101D11D11C01
11Q0000Q1101010111010C0010001
1000T000000000001101D10010C01
1000000000000000CC01000000001
11Q00Q01110101011101C11110111
1111K111 1110101 111111Q000Q1
11111111 1111111 111111111111
```

---

## 🕹️ Controls & Navigation

| Key Binding | Action |
| :--- | :--- |
| `W` / `S` | Move Forward / Backward (with bounding-box collision detection) |
| `A` / `D` | Strafe Left / Right |
| `←` / `→` (Arrows) | Rotate View Camera Left / Right |
| `Mouse` | Smooth First-Person Camera Rotation |
| `E` / `Space` | Interact (Open Sliding Doors / Activate Portals) |
| `ESC` / `[X]` Window | Gracefully Terminate Audio Streams, Free Resources, and Exit |

---

## 🚀 Compilation & Execution

### Prerequisites

Ensure you have the required X11 development libraries installed (Linux/WSL):

```bash
sudo apt-get install build-essential libx11-dev libxext-dev libbsd-dev libsoundio-dev
```

### Build Rules & Launching

Compile the executable binary using the included `Makefile`:

```bash
make
./cub3D assets/maps/subject.cub
```

*   `make`: Compiles the engine with MiniLibX and BASS dynamic library linking.
*   `make bonus`: Compiles the engine with extra features.
*   `make clean`: Removes object files (`.o`).
*   `make fclean`: Removes object files and binary target.
*   `make re`: Recompiles the entire codebase from scratch.

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
