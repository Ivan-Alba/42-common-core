# libft — Recreating the C Standard Library

<p align="center">
  <img src="https://img.shields.io/badge/Project-Libft-000000?style=for-the-badge&logo=42&logoColor=white" alt="Libft" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview

The standard C library provides a fundamental set of functions handling string manipulation, memory management, I/O processing, and data type conversions. In the **42 Network**, utilization of these standard functions is strictly forbidden. 

**libft** is the very first project of the Common Core. The objective was to design, test, and compile a custom, fully compliant standalone implementation of these library functions from scratch. This static library (libft.a) serves as the foundational dependency layer for every subsequent systems project in the curriculum (such as *minishell*, *cub3D*, and *ft_irc*).

---

## 🛠️ Library Architecture

The library is segmented into three distinct logical phases: standard libc functions, additional utility expansions, and advanced data structures (linked lists).

### 1. Standard Libc Functions
Re-engineering of core POSIX and ISO C standard functions, preserving exact behavior, edge-case safety, and return values.

| Character Verification | Memory Manipulation | String Operations & Conversions |
| :--- | :--- | :--- |
| ft_isalpha / ft_isdigit | ft_memset / ft_bzero | ft_strlen / ft_strchr / ft_atoi |
| ft_isalnum / ft_isascii | ft_memcpy / ft_memmove | ft_strrchr / ft_strncmp |
| ft_isprint / ft_toupper | ft_memchr / ft_memcmp / ft_memrchr | ft_strnstr / ft_strdup |
| ft_tolower | ft_calloc (Dynamic allocation) | ft_strlcpy / ft_strlcat |

### 2. Non-Standard Utilities
Advanced functional extensions mapping to complex memory allocations and high-level string manipulation.

*   ft_substr: Allocates and returns a substring from a precise index string pointer.
*   ft_strjoin: Concatenates two distinct strings into a new dynamically allocated buffer.
*   ft_strtrim: Trims a specific set of characters from both the prefix and suffix of a string.
*   ft_split: Tokenizes a string into a null-terminated array of strings using a delimiter character.
*   ft_itoa: Converts an integer arithmetic primitive into a dynamically allocated string representation.
*   ft_strmapi / ft_striteri: Map and iterative function pointers applied abstractly across string indices.
*   ft_putchar_fd / ft_putstr_fd / ft_putendl_fd / ft_putnbr_fd: Safe I/O descriptor writers.

### 3. Bonus Phase: Dynamic Data Structures (Linked Lists)
Implementation of structural nodes to transition from static arrays to dynamic, heap-allocated singly linked lists. El nodo base (t_list) está compuesto por un puntero genérico (*content) para almacenar los datos del elemento y un puntero estructural (*next) que enlaza directamente con la siguiente dirección de memoria de la lista.

*   **List Lifecycles:** ft_lstnew (Instantiation), ft_lstadd_front / ft_lstadd_back (Node insertion).
*   **Inspections:** ft_lstsize (Length counting), ft_lstlast (Tail pointer retrieval).
*   **Memory Eviction:** ft_lstdelone (Node isolation deletion), ft_lstclear (Full structural wipe).
*   **Functional Mechanics:** ft_lstiter (Iteration mapping), ft_lstmap (New dynamic structural transformations).

---

## 🧠 Key Technical Challenges & Rigor

> [!IMPORTANT]
> **Pointer Arithmetic & Boundary Security**<br>
> Recreating functions like ft_memmove demanded precise handling of overlapping memory segments (where the source pointer and destination pointer reside in the same block). I resolved this by checking buffer orientation bounds and implementing back-to-front byte copying loops whenever memory collision risk was present.

*   **Defensive Design & Null Checking:** To prevent segmentation faults in high-level modules, every single function is engineered defensively to safely handle NULL pointers, string boundary overflows, and integer wrapping limits.
*   **Heap Protection:** Every dynamic allocation allocation (malloc) inside functions like ft_split or ft_itoa is strictly guarded. In case of a mid-execution allocation failure (e.g., memory exhaustion), the engine executes an immediate cascading clean-up loop to free all previously allocated nodes, completely preventing memory leaks before returning NULL.
*   **Compilation Strictness:** Adheres entirely to the 42 Norminette style guide and compiles flawlessly with -Wall -Wextra -Werror flags.

---

## 🚀 Usage Guide

The repository includes a modular automation script (Makefile) designed for safe, multi-architecture compilation.

### ⚙️ Compilation Commands

*   **make**: Compile the core standard and utility functions into libft.a
*   **make bonus**: Compile the library including the advanced Linked List bonus data structures
*   **make clean**: Remove all transitional object (.o) compilation files
*   **make fclean**: Force a full clean-up removing object files and the libft.a binary
*   **make re**: Trigger a complete clean re-build of the entire library

### 🔗 Linking the Library to Your Project

Once the static library has been compiled using the commands above, you can implement it inside an external C application. Include the library header file (#include "libft.h") in your source code and link the generated binary during the compilation phase by pointing to the library folder:

gcc -Wall -Wextra -Werror main.c -I./libft -L./libft -lft -o my_program
