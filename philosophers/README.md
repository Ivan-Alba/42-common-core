# Philosophers — Concurrent Programming & Process Synchronization

<p align="center">
  <img src="https://img.shields.io/badge/Project-Philosophers-000000?style=for-the-badge&logo=42&logoColor=white" alt="Philosophers" />
  <img src="https://img.shields.io/badge/Language-C-00599C?style=for-the-badge&logo=c&logoColor=white" alt="C" />
  <img src="https://img.shields.io/badge/Concurrency-Threads%20%26%20Processes-FF6F00?style=for-the-badge" alt="Concurrency" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📖 Overview
**Philosophers** is a multi-threaded and multi-process C implementation of Edsger Dijkstra's classic Dining Philosophers Problem. The project explores core system programming concepts including concurrent execution, resource allocation strategies, race condition prevention, and deadlock avoidance.

The mandatory module implements thread-based parallelism with shared memory using POSIX Threads (`pthreads`) and **Mutexes**. The bonus module approaches the problem through strict process isolation using **Child Processes** (`fork`), inter-process communication, and named **POSIX Semaphores**.

---

## 📋 Technical Specifications & Key Features

*   **Dual Concurrency Models**: Multithreading with shared memory (`pthreads`) in mandatory vs. process-level isolation (`fork`) without shared memory in bonus.
*   **Algorithmic Deadlock Avoidance**:
    *   *Mandatory*: Even/Odd asymmetric fork locking logic breaks circular wait conditions across ring-topology mutexes.
    *   *Bonus*: Resource-counting pool managed by a single central named POSIX semaphore (`/forks_sem`).
*   **Synchronized Barrier Launch & Phase Throttling**: Gate-locking barrier mechanism (`start_lock` / `start_sem`) ensures all threads/processes launch simultaneously at $t = 0$. Micro-delays prevent initial CPU scheduling bottlenecks.
*   **Hybrid Per-Process Supervisor**: Asynchronous monitoring routines running as dedicated threads/sub-threads that inspect starvation thresholds ($time\_to\_die$) with microsecond-precision polling ($200\mu\text{s}$) without blocking execution loops.
*   **Atomic Logging & Lock-on-Death**: Dedicated mutexes/semaphores protect output streams against data races. The write lock is permanently retained upon a philosopher's death, freezing the console to prevent garbled or out-of-order trace logs.
*   **Inter-Process Signaling & IPC Cleanups**: Process tree supervision via `waitpid` inspecting exit status codes, propagating fast termination via `SIGKILL`, and defensive Kernel IPC cleanup (`sem_unlink`).

---

## 🛠️ Project Architecture

```text
.
├── philo/                      # Mandatory module: Multithreading & Mutexes
│   ├── Makefile                # Compilation rules for mandatory binary
│   ├── philosophers.h          # Header defining t_philo, t_data structures, and prototypes
│   ├── main.c                  # Entry point, circular ring topology linking, and cleanup
│   ├── check_args.c            # Strict numeric input validation and threshold checks (>= 60ms)
│   ├── run.c                   # Thread creation loops, start barrier synchronization, and execution
│   ├── actions.c               # Core state machine (eat, sleep, think) with asymmetric mutex locking
│   ├── monitor.c               # Asynchronous supervisor thread tracking starvation and meal quotas
│   └── utils.c                 # Timestamp calculators (gettimeofday), error loggers, and memory release
│
└── philo_bonus/                # Bonus module: Multiprocessing & Semaphores
    ├── Makefile                # Compilation rules for bonus binary
    ├── philosophers.h          # Bonus header defining process contexts and semaphore handles
    ├── main.c                  # Bonus entry point, child waitpid supervisor, and signal propagation
    ├── run.c                   # Process spawning loops (fork), start barrier, and sub-thread launch
    ├── actions.c               # Isolated child process action loops using POSIX counting semaphores
    ├── monitor.c               # Per-process monitoring thread triggering exit codes on starvation
    ├── proccess_utils.c        # Semaphore initialization, sem_unlink cleanup handlers, and SIGKILL routines
    └── utils.c                 # Precision time utilities, defensive memory free, and IPC unlinking
```

---

## 💻 Simulation Rules & Arguments

The program accepts the following command-line parameters:

```bash
./philo number_of_philosophers time_to_die time_to_eat time_to_sleep [number_of_times_each_philosopher_must_eat]
```

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

```bash
cd philo
make
./philo 4 410 200 200
```

### Bonus (Processes & Semaphores)

Navigate to the `philo_bonus/` directory and compile the process-isolated version:

```bash
cd philo_bonus
make
./philo_bonus 5 800 200 200 7
```

### Build Rules (Both Modules)

*   `make`: Compiles the binary executable.
*   `make clean`: Removes object files (`.o`).
*   `make fclean`: Removes object files and the target executable.
*   `make re`: Recompiles the binary from scratch.

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
