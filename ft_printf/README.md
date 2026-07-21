# ft_printf — Recreating the C Standard Library printf

<p align="center">
  <img src="https://img.shields.io/badge/Project-ft__printf-000000?style=for-the-badge&logo=42&logoColor=white" alt="ft_printf" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview

An implementation of a custom `ft_printf` function that replicates the behavior of the standard C library's `printf`. This project introduces variadic arguments in C using the `<stdarg.h>` library, strict system write evaluation, and meticulous heap memory management. 

This version supports all mandatory specifiers as well as comprehensive management of width, precision, and formatting flags required by the **Bonus** section of the 42 subject.

---

## 📋 Technical Specifications & Requirements

The implementation replicates the exact string formatting and output stream behavior of the native system `printf`. The architecture is fully protected to handle stream write errors and avoid memory leaks.

### Mandatory Conversions

| Specifier | Description | Implementation Detail |
| :--- | :--- | :--- |
| `%c` | Character | Prints a single character to the standard output. |
| `%s` | String | Prints a string of characters. If `NULL`, outputs `(null)`. |
| `%p` | Pointer | Prints a memory address in hex format. Outputs `(nil)` if `NULL`. |
| `%d` | Signed Integer | Prints a base 10 signed integer. |
| `%i` | Signed Integer | Prints a base 10 signed integer equivalent to `%d`. |
| `%u` | Unsigned Integer | Prints an unsigned decimal integer. |
| `%x` | Lowercase Hex | Converts and prints an integer in lowercase base 16 hexadecimal. |
| `%X` | Uppercase Hex | Converts and prints an integer in uppercase base 16 hexadecimal. |
| `%%` | Literal Percent | Prints a single `%` character, bypassing any active flags. |

### Bonus Flags & Attributes

The parser safely evaluates and handles any combined implementation of the following flags:

*   **Field Modification Flags (`-`, `0`, `.`)**:
    *   `-`: Left-justifies the output within the specified minimum field width.
    *   `0`: Pads the output with leading zeros instead of spaces (overridden by precision on integers).
    *   `.`: Specifies the minimum number of digits for numbers or max character length for strings.
*   **Format Presentation Flags (`#`, ` `, `+`)**:
    *   `#`: Alternate form prefix for hex (`0x` for `%x` and `0X` for `%X`). Ignored if the value is 0.
    *   ` `: Places a blank space before a positive signed number if no sign flag is triggered.
    *   `+`: Forces a sign modifier (`+` or `-`) to precede signed number representations.
*   **Minimum Field Width**:
    *   An integer value defining the absolute minimum number of characters required for the field output.

---

## 🛠️ Project Architecture

```text
.
├── libft/                  # Core library containing basic utility functions
├── Makefile                # Automation build script compiling both mandatory and bonus files
├── ft_printf.c             # Primary engine core and mandatory router logic
├── ft_printf.h             # Header file containing t_flags structure and prototypes
├── ft_check_flags_bonus.c  # Parser that decodes bonus width, precision, and alignment attributes
├── ft_print_flags_bonus.c  # Format routing controls for characters, strings, and pointers under bonus rules
├── ft_int_flags_bonus.c    # Sign, space, zero-fill, and truncation layouts for integers (%d, %i, %u)
├── ft_hexa_flags_bonus.c   # Prefix injection, width padding, and alignment for hex values (%x, %X)
├── ft_utils_bonus.c        # Internal layout math, padding calculations, and specialized bonus helpers
├── ft_free_bonus.c         # Clean memory release routers for string buffers during error interruptions
├── ft_utils.c              # Protected system write wrappers and standard string/pointer readers
└── ft_utoa.c               # Dedicated allocator translating unsigned integers into safe string buffers
```
---

## 🚀 Compilation & Usage

The project builds a static library archive called `libftprintf.a`. The `Makefile` relies on optimized rules to compile only modified source items.

### Compilation Commands

To compile the mandatory part:
```bash
make
```

To compile including the bonus features:
```bash
make bonus
```

To strip down and clear all middle object files (.o) generated during build operations:
```bash
make clean
```

To clean out both intermediate object files and the resulting static archive file:
```bash
make fclean
```

To perform a complete project cleanup and rebuild all modules from the ground up:
```bash
make re
```

### Source Integration

To integrate this library within an external project development, include its prototype definitions header and link the archive binaries at compilation:

```c
#include "ft_printf.h"

int main(void)
{
    // Combined implementation example using both mandatory and bonus features
    ft_printf("Hello %-10s number: %+05d in hex: %#x\n", "World", 42, 255);
    return (0);
}
```

Compile your code linking against the libftprintf.a static archive path:
```bash
gcc main.c libftprintf.a -o project_run
```

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
