# 🎮 Proyecto cub3D
## *Simulación 3D en C con Raycasting*

## 📚 Descripción General

**cub3D** es un proyecto de la Escuela 42 basado en la técnica de **raycasting**, que permite crear una simulación 3D en un entorno 2D, inspirada en FPS clásicos como *Wolfenstein 3D* :contentReference[oaicite:0]{index=0}.  
El usuario puede moverse libremente por un mapa `.cub`, visualizando paredes con texturas diferentes y viendo el suelo y el techo con colores RGB personalizados :contentReference[oaicite:1]{index=1}.

---

## 🛠️ Requisitos y Restricciones

- Utiliza **MiniLibX (MLX)** para renderizar gráficos.
- Debe mostrar **texturas diferentes** para los muros norte, sur, este y oeste.
- Los colores de suelo y techo se definen con valores RGB.
- **Teclas**:
  - `WASD` para moverse.
  - Flechas izquierda/derecha para rotar la vista.
  - `ESC` o cruz de la ventana para cerrar el programa correctamente.
- Solo acepta mapas con extensión `.cub`, que deben estar **cerrados** y ser válidos :contentReference[oaicite:2]{index=2}.

---

## 🗺️ Ejemplo de mapa `.cub`

```text
NO ./path_to_the_north_texture
SO ./path_to_the_south_texture
WE ./path_to_the_west_texture
EA ./path_to_the_east_texture
F 220,100,0
C 225,30,0
1111111111111111111111111
1000000000110000000000001
1111111111111111111111111
```

# 🧱 cub3D – Motor 3D con Raycasting en C

## 🗺️ Formato del Mapa `.cub`

- `NO`, `SO`, `WE`, `EA`: rutas a las texturas para muros **Norte, Sur, Oeste y Este**.
- `F`, `C`: colores RGB para el **suelo (Floor)** y el **techo (Ceiling)**.
- El cuerpo del mapa se compone de los siguientes caracteres:
  - `1`: muro
  - `0`: suelo
  - `N/S/E/W`: posición inicial y orientación del jugador

Ejemplo:

```text
NO ./textures/north.xpm
SO ./textures/south.xpm
WE ./textures/west.xpm
EA ./textures/east.xpm
F 220,100,0
C 225,30,0

1111111
1000001
10N0001
1111111
```

# 🎯 Algoritmo de Raycasting – Proyecto cub3D

## 🧠 Fundamento

El núcleo del proyecto **cub3D** es el algoritmo de **raycasting**, que permite simular un entorno tridimensional desde un mapa bidimensional.

### 🔎 Pasos del Algoritmo

- 🔭 **Dirección del rayo:** Se calcula para cada columna de píxeles según el ángulo de rotación (`rotAngle`) y el campo de visión (`FOV`).
- 📐 **Intersecciones:** Se detectan las colisiones horizontales y verticales con las paredes del mapa.
- 🎯 **Selección más cercana:** Se elige la intersección más próxima y se corrige la distancia con efecto **fish-eye**.
- 🧱 **Proyección:** Se representa en pantalla una franja vertical que simula una pared.
- 🧮 **Corrección angular:** Se aplica el coseno del ángulo para evitar la distorsión tipo “pecera” (**fishbowl**).

---

## 🎨 Texturizado y Proyección

- Una vez calculada la distancia al muro, se determina qué textura aplicar.
- Se usan coordenadas `xcord` y `ycord`, con funciones como `fmodf`, y dimensiones de la imagen (`width`, `height`).
- Cada franja vertical se renderiza con la porción correcta de textura correspondiente al muro impactado.

---

## 🎮 Controles del Juego

| Tecla       | Acción                                        |
|-------------|-----------------------------------------------|
| `W`, `A`, `S`, `D` | Mover al jugador (adelante, izquierda, atrás, derecha) |
| ⬅️ / ➡️     | Rotar la vista                               |
| `ESC` / ❌  | Salir del programa de forma segura            |

---

## 📦 Compilación y Ejecución

Para compilar el proyecto, ejecuta en terminal:

```bash
make
```

Para ejecutar el juego, ejecuta en terminal
```bash
./cub3d mapa.cub
```

## 🎓 Aprendizajes Clave

✔️ Implementación desde cero de un **motor de raycasting**  
✔️ Conversión de mapas 2D en una experiencia visual **3D en primera persona**  
✔️ Gestión eficiente de **texturas**, **perspectiva** y **corrección de distorsión angular**  
✔️ Uso práctico de **vectores**, **trigonometría** (`sin`, `cos`) y estructuras optimizadas para **renderizado en tiempo real**

---

## 📌 Conclusión

**cub3D** es una aventura técnica ideal para dominar los fundamentos de los **motores gráficos clásicos**, el **renderizado optimizado**, y el **diseño de videojuegos** en lenguaje C.

🚀 ¡Construye tu propio mundo tridimensional en C y conviértete en el maestro del raycasting! 🎮✨

---

# 📝 Créditos

- 💻 **Proyecto:** Minishell – Escuela 42  
- ✍️ **Autor original:** Miriam Fernández Pérez  

---

