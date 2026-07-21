# Pipex — Handling UNIX Pipes and Process Execution

<p align="center">
  <img src="https://img.shields.io/badge/Project-Pipex-000000?style=for-the-badge&logo=42&logoColor=white" alt="Pipex" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview

**Pipex** is a project in the 42 curriculum designed to re-create the mechanism of the UNIX pipeline operator (`|`) in C. It deepens the understanding of system calls, process creation (`fork`), file descriptor redirection (`dup2`), process execution (`execve`), inter-process communication using anonymous pipes (`pipe`), and process synchronization (`wait`).

This implementation completely reproduces the execution pipeline of shell commands and includes full support for the **Bonus** requirements: multiple chained pipeline stages (handling $N$ commands) and support for heredoc input redirection (`here_doc`).

---

## 📋 Technical Specifications & Requirements

The program mirrors the behavior and syntax of shell command pipelines, handling input and output redirection safely while isolating execution environments using child processes.

### Shell Equivalences

* **Mandatory Execution Pipeline:**
  The execution of `./pipex file1 cmd1 cmd2 file2` behaves identically to the shell command:
  ```bash
  < file1 cmd1 | cmd2 > file2
  ```

* **Multiple Pipeline Stages (Bonus):**
  The execution of `./pipex file1 cmd1 cmd2 cmd3 ... cmdN file2` behaves identically to:
  ```bash
  < file1 cmd1 | cmd2 | cmd3 ... | cmdN > file2
  ```

* **Here_doc Input Redirection (Bonus):**
  The execution of `./pipex here_doc LIMITER cmd1 cmd2 file2` behaves identically to:
  ```bash
  cmd1 << LIMITER | cmd2 >> file2
  ```

---

## 🛠️ Project Architecture

```text 
.
├── libft/           # Custom core utility functions and get_next_line implementation
├── Makefile         # Build automation script with mandatory and bonus rules
├── pipex.h          # Header file containing system includes, t_cmd, and t_pipex structs
├── main.c           # Program entry point, process spawning loop, heredoc reader, and file setup
├── init_data.c      # Environment PATH parser, pipe creation, and command resolution logic
├── execute.c        # File descriptor redirection engine and execve launcher
└── free_data.c      # Safe memory deallocator and pipe file descriptor cleanup wrappers
```

---

## 🚀 Compilation & Usage

The project builds the executable binary `pipex`. The `Makefile` relies on optimized compilation rules to ensure modular re-building.

### Compilation Commands

To compile the mandatory part:
```bash
make
```

To compile including bonus functionality (multiple pipes & heredoc):
```bash
make bonus
```

To remove object files (.o) created during build operations:
```bash
make clean
```

To remove object files and the output executable binary:
```bash
make fclean
```

To perform a complete clean rebuild of all targets:
```bash
make re
```

---

## 🧪 Execution Examples

### 1. Mandatory Usage (2 Commands)

To execute a basic two-command pipeline:
```bash
./pipex infile "ls -l" "wc -l" outfile
```

This takes the content of `infile`, passes it as `stdin` to `ls -l`, pipes the output to `wc -l`, and writes the final output to `outfile`.

---

### 2. Bonus Usage: Multiple Pipelines

To chain an arbitrary number of command pipes:
```bash
./pipex infile "cat" "grep 42" "wc -l" outfile
```

Equivalence in standard shell:
```bash
< infile cat | grep 42 | wc -l > outfile
```

---

### 3. Bonus Usage: Here_doc Mode

To redirect user input dynamically until reaching a delimiter tag:
```bash
./pipex here_doc END "grep hello" "wc -w" outfile
```

Equivalence in standard shell:
```bash
 grep hello << END | wc -w >> outfile
```

---

## 🛡️ Error Handling

The program handles edge cases gracefully, exiting safely without leaving dangling child processes or leaking open file descriptors or allocated heap memory:

* Non-existent input files (`infile`).
* Permission denied errors for reading or writing files (`EACCES`).
* Invalid command execution paths (`Command not found`).
* Allocation failures (`malloc`) or system pipe/fork creation errors (`pipe`, `fork`).

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
