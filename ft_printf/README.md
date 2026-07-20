# ft_printf - 42 Project

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

## 🚀 Compilation & Usage

The project builds a static library archive called `libftprintf.a`. The `Makefile` relies on optimized rules to compile only modified source items.

### Compilation Commands

To compile the entire codebase including all mandatory and bonus functionalities:
```bash
make
