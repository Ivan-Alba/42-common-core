# Philosophers — Concurrent Programming & Process Synchronization

<p align="center">
  <img src="https://img.shields.io/badge/Project-Philosophers-000000?style=for-the-badge&logo=42&logoColor=white" alt="Philosophers" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Concurrency-Threads%20%26%20Processes-FF6F00?style=for-the-badge" alt="Concurrency" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview
**Philosophers** is a multi-threaded and multi-process C implementation of Edsger Dijkstra's classic Dining Philosophers Problem. The project explores the core principles of concurrent programming, resource allocation, race condition prevention, and deadlock avoidance.

The mandatory implementation utilizes POSIX Threads (`pthreads`) and **Mutexes** with shared memory, while the bonus implementation approaches the problem using **Child Processes** (`fork`) and **POSIX Semaphores** to enforce strict process isolation and inter-process synchronization.

---

## 📋 Technical Specifications & Key Features

*   **Concurrency Models**: Dual architecture featuring thread-based parallelism with shared memory (`pthreads`) in mandatory and process-based isolation (`fork`) in bonus.
*   **Thread Synchronization & Safety**: Complete protection against race conditions using POSIX Mutexes (`pthread_mutex`) to lock critical sections (e.g., fork resources, state updates, timestamp logging).
*   **Inter-Process Communication & Semaphores**: Named POSIX semaphores (`sem_open`, `sem_wait`, `sem_post`) managing shared resource pools across isolated process boundaries.
*   **Real-Time Monitoring Thread**: Asynchronous monitor routines dedicated to tracking starvation thresholds ($time\_to\_die$) and verifying global meal saturation limits without blocking action routines.
*   **High-Precision Time Tracking**: Microsecond-precision timekeeping utilities built on `gettimeofday` to prevent drift and ensure accurate event logging.
*   **Clean Resource Lifecycle Management**: Deterministic cleanup routines ensuring zero memory leaks (`valgrind`), leak-free mutex destruction, and proper process termination routines (`waitpid`, `kill`).

---

## 🛠️ Project Architecture

[TRIPLE_BACKTICKS]text
.
├── philo/                      # Mandatory module: Multithreading & Mutexes
│   ├── Makefile                # Compilation rules for mandatory binary
│   ├── philosophers.h          # Header defining t_philo, t_table structures, and prototypes
│   ├── main.c                  # Entry point, argument parsing initialization, and execution trigger
│   ├── check_args.c            # Strict numeric input validation and boundary checking
│   ├── run.c                   # Thread creation loops, simulation lifecycle, and join routines
│   ├── actions.c               # Core state machine routines (eat, sleep, think) with mutex locking
│   ├── monitor.c               # Dedicated asynchronous supervisor thread checking for starvation
│   └── utils.c                 # Precision timestamp calculators, custom sleep routines, and string utilities
│
└── philo_bonus/                # Bonus module: Multiprocessing & Semaphores
    ├── Makefile                # Compilation rules for bonus binary
    ├── philosophers.h          # Bonus header with process/semaphore structural definitions
    ├── main.c                  # Bonus entry point and parent process supervisor routing
    ├── run.c                   # Process spawning loops (fork) and process exit wait handlers
    ├── actions.c               # Isolated child process lifecycle execution loops
    ├── monitor.c               # Per-process starvation monitoring routine
    ├── proccess_utils.c        # Semaphore management wrappers, creation, and unlink handlers
    └── utils.c                 # Timestamping utilities and atomic printing protections
[TRIPLE_BACKTICKS]

---

## 💻 Simulation Rules & Arguments

The program accepts the following command-line parameters:

[TRIPLE_BACKTICKS]bash
./philo number_of_philosophers time_to_die time_to_eat time_to_sleep [number_of_times_each_philosopher_must_eat]
[TRIPLE_BACKTICKS]

| Argument | Description | Unit |
| :--- | :--- | :--- |
| `number_of_philosophers` | Total number of philosophers (and forks) present at the table | Integer |
| `time_to_die` | Max elapsed time since last meal start before a philosopher dies of starvation | Milliseconds |
| `time_to_eat` | Time spent holding two forks to eat | Milliseconds |
| `time_to_sleep` | Time spent sleeping after finishing a meal | Milliseconds |
| `[number_of_times_each_philosopher_must_eat]` | *(Optional)* Simulation terminates when all philosophers eat at least this many times | Integer |

---

## 🚀 Compilation & Usage

### Mandatory (Threads & Mutexes)

Navigate to the `philo/` directory and compile using the included `Makefile`:

[TRIPLE_BACKTICKS]bash
cd philo
make
./philo 4 410 200 200
[TRIPLE_BACKTICKS]

### Bonus (Processes & Semaphores)

Navigate to the `philo_bonus/` directory and compile the process-isolated version:

[TRIPLE_BACKTICKS]bash
cd philo_bonus
make
./philo_bonus 5 800 200 200 7
[TRIPLE_BACKTICKS]

### Build Rules (Both Modules)

*   `make`: Compiles the binary executable.
*   `make clean`: Removes object files (`.o`).
*   `make fclean`: Removes object files and the target executable.
*   `make re`: Recompiles the binary from scratch.

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
