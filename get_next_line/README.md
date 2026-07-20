# get_next_line — Reading a File Descriptor Line by Line

<p align="center">
  <img src="https://img.shields.io/badge/Project-get__next__line-000000?style=for-the-badge&logo=42&logoColor=white" alt="get_next_line" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview
An implementation of a custom C function that reads a file descriptor line by line, dynamic buffer allocation handling, and static array multi-descriptor operations. This project provides deep control over file input streams, data persistence across subsequent dynamic calls using static variables, and runtime heap safety.

This version supports execution under arbitrary variable sizes (`BUFFER_SIZE`) as well as simultaneous tracking of multiple individual streams required by the **Bonus** section of the 42 subject.

---

## 📋 Technical Specifications & Requirements

The function is designed to read text inputs from a target descriptor efficiently, returning the complete line including its terminating newline character (`\n`) unless the end of the file has been reached.

### Performance Indicators

*   **Dynamic Reads**: The function loops and fetches segments from disk exactly using the defined `BUFFER_SIZE` macro variable configured during compile time.
*   **Static Accumulators**: Relies on a tracking memory layout to save remaining chunks across different execution frames, safely stitching chunks together.
*   **Leak Defenses**: Protects against unexpected runtime events (such as premature descriptor closures or unexpected memory exhaustion) by systematically freeing buffers to guarantee zero memory leaks.

---

## 🛠️ Project Architecture

```text
.
├── get_next_line.c
├── get_next_line.h
├── get_next_line_utils.c
├── get_next_line_bonus.c
├── get_next_line_bonus.h
└── get_next_line_utils_bonus.c
```

*   **get_next_line.c**: Core file handling the primary extraction loop (`read_next_line`) and the main routing function (`get_next_line`).
*   **get_next_line.h**: Central header file defining the `t_list` tracking configuration structure, inclusion of basic standard system libraries, and function definitions.
*   **get_next_line_utils.c**: Memory helper utilities carrying out allocation cleanup routers (`free_and_out`) and string verification routines (`is_next_line`).
*   **get_next_line_bonus.c**: Replicated core file targeted for bonus evaluation, containing static vector architectures designed to support concurrent multi-stream connections.
*   **get_next_line_bonus.h**: Header file targeted for the bonus module containing its custom guards and structural blueprints.
*   **get_next_line_utils_bonus.c**: Specific utility functions duplicated and compiled independently to build the bonus system binary cleanly.

---

## 🚀 Compilation & Usage

This project is not packaged into an archive library; instead, it is compiled directly alongside your main program architecture, injecting the specific `BUFFER_SIZE` requirements dynamically.

### Basic Compilation

To compile the mandatory requirements using the regular source files, pass the source components directly to your C compiler:

```bash
gcc -Wall -Wextra -Werror -D BUFFER_SIZE=42 main.c get_next_line.c get_next_line_utils.c -o gnl_run
```
### Bonus Compilation (Multiple Descriptors)

To test the multi-descriptor capabilities using the bonus files, swap out the file paths during compilation:

```bash
gcc -Wall -Wextra -Werror -D BUFFER_SIZE=32 main.c get_next_line_bonus.c get_next_line_utils_bonus.c -o gnl_bonus_run
```

### Source Integration

To interact with this library inside an external project environment, include the respective header file and pass your targeted open stream descriptor directly into the reader routine:

```c
#include "get_next_line.h"
#include <fcntl.h>
#include <stdio.h>
   
int main(void)
{
    int     fd;
    char    *line;
    
    fd = open("sample.txt", O_RDONLY);
    while ((line = get_next_line(fd)) != NULL)
    {
        printf("%s", line);
        free(line);
    }
    close(fd);
    return (0);
}
```
