# C++ Modules — Object-Oriented Programming Suite (C++98)

<p align="center">
  <img src="https://img.shields.io/badge/Suite-CPP_Modules-000000?style=for-the-badge&logo=42&logoColor=white" alt="CPP Modules" />
  <img src="https://img.shields.io/badge/Language-C%2B%2B98-00599C?style=for-the-badge&logo=cplusplus&logoColor=white" alt="C++98" />
  <img src="https://img.shields.io/badge/Paradigms-OOP%20%7C%20Templates%20%7C%20STL-FF6F00?style=for-the-badge" alt="Paradigms" />
  <img src="https://img.shields.io/badge/Grade-100%20%2F%20100-success?style=for-the-badge" alt="100/100" />
</p>

---

## 📖 Overview

The **C++ Modules** suite represents a comprehensive transition from imperative C programming into **Object-Oriented Programming (OOP)**, memory management paradigms, and template metaprogramming. Adhering strictly to the **C++98 standard**, this repository tracks progressive mastery across 10 distinct modules (`cpp_00` through `cpp_09`).

Starting from basic class structures and namespace encapsulation, the curriculum advances through dynamic memory management, canonical class forms, inheritance hierarchies, runtime polymorphism, C++ casting operators, template definitions, and Standard Template Library (STL) container manipulation.

---

## 📋 Core C++98 Standards & Guidelines

All exercises across every module strictly comply with the following architectural constraints required by the 42 School standard:

*   **Compiler & Flags**: All projects are compiled using `c++` with the mandatory flags `-Wall -Wextra -Werror -std=c++98`.
*   **Orthodox Canonical Class Form**: Unless specified otherwise, classes implement the default constructor, copy constructor, copy assignment operator, and destructor.
*   **Forbidden Features**: Modern C++ standards (C++11 and beyond), external libraries (Boost), non-standard functions (`printf`, `malloc`, `free`), and `using namespace std;` in header files are strictly prohibited.
*   **Memory Integrity**: Zero memory leaks allowed; dynamic memory allocated via `new` / `new[]` is systematically freed using `delete` / `delete[]`.

---

## 🗺️ Curriculum Architecture & Module Roadmap

A breakdown of the core computer science concepts covered in each module:

| Module | Core Topic | Key Concepts & Applied Mechanics |
| :--- | :--- | :--- |
| **`cpp_00`** | **Namespaces & Classes** | Basic syntax, `std::cout`/`std::cin`, member functions, static members, class attributes, and member initialization lists. |
| **`cpp_01`** | **Memory & Pointers** | Stack vs. Heap allocation, pointers to members, references, `new`/`delete`, string streams, and file I/O operations (`std::ifstream`/`std::ofstream`). |
| **`cpp_02`** | **Fixed-Point & Overloading** | Ad-hoc polymorphism, operator overloading (`+`, `-`, `*`, `/`, `++`, `--`, etc.), and Fixed-Point number representation. |
| **`cpp_03`** | **Inheritance** | Base and derived classes, access specifiers (`public`, `protected`, `private`), initialization chains, and constructor/destructor ordering. |
| **`cpp_04`** | **Subtype Polymorphism** | Virtual functions, abstract base classes (ABCs), pure virtual methods, interfaces, deep vs. shallow copying, and virtual destructors. |
| **`cpp_05`** | **Exceptions & Nested Classes** | Exception handling (`try`, `catch`, `throw`), custom exception classes inheriting from `std::exception`, and form/bureaucracy state machines. |
| **`cpp_06`** | **C++ Casts** | Explicit type conversion: `static_cast`, `dynamic_cast`, `reinterpret_cast`, and `const_cast`, along with runtime type identification (RTTI). |
| **`cpp_07`** | **Templates** | Function templates, class templates, template instantiation, and explicit specialization. |
| **`cpp_08`** | **STL Containers & Algorithms** | Standard Template Library containers (`std::vector`, `std::list`, `std::deque`), iterators, and generic algorithms (`std::find`, `std::for_each`). |
| **`cpp_09`** | **Advanced STL Applications** | Complex algorithmic problems using targeted containers (`std::stack`, `std::map`, `std::deque`) like Reverse Polish Notation and Ford-Johnson sorting. |

---

## 🛠️ Repository Directory Structure

[TRIPLE_BACKTICKS]text
cpp_module/
├── cpp_00/          # Namespaces, Classes, Member Functions, std::iostream
│   ├── ex00/        # Megaphone (string manipulation & output streams)
│   ├── ex01/        # My Awesome PhoneBook (class state & contact management)
│   └── ex02/        # Account Log Re-creation (class member analysis)
│
├── cpp_01/          # Memory Allocation, References, Pointers to Members
│   ├── ex00/        # Zombie allocation (Stack vs Heap allocation mechanics)
│   ├── ex01/        # Zombie Horde (array allocation with new[])
│   ├── ex02/        # Reference vs Pointer memory addressing
│   ├── ex03/        # Weapon & Human references vs pointers
│   ├── ex04/        # File string replacer using fstream
│   ├── ex05/        # Harl (pointers to member functions)
│   └── ex06/        # Harl Filter (switch-case fallback routing)
│
├── cpp_02/          # Ad-hoc Polymorphism, Operator Overloading, Fixed-Point
│   ├── ex00/        # Raw Fixed-Point number representation
│   ├── ex01/        # Fractional and Floating-Point bitwise conversions
│   ├── ex02/        # Full arithmetic, comparison, and increment operator overloading
│   └── ex03/        # Point-in-Triangle BSP (Binary Space Partitioning) algorithm
│
├── cpp_03/          # Inheritance & Derived Class Hierarchies
│   ├── ex00/        # ClapTrap base class
│   ├── ex01/        # ScavTrap derived class (constructor execution chain)
│   ├── ex02/        # FragTrap specialized derived class
│   └── ex03/        # DiamondTrap (Diamond inheritance resolution)
│
├── cpp_04/          # Subtype Polymorphism, Abstract Classes, Interfaces
│   ├── ex00/        # Polymorphic Animal/Dog/Cat hierarchy
│   ├── ex01/        # Deep copy validation for dynamic Brain attributes
│   ├── ex02/        # Abstract Animal class (pure virtual destructors)
│   └── ex03/        # Interface implementation (MateriaSource & Character inventory)
│
├── cpp_05/          # Exception Handling & Bureaucracy Workflows
│   ├── ex00/        # Bureaucrat class with custom high/low grade exceptions
│   ├── ex01/        # Form signing workflow and exception propagation
│   ├── ex02/        # Executable Forms (Shrubbery, Robotomy, Presidential)
│   └── ex03/        # Intern factory class generating concrete forms
│
├── cpp_06/          # Type Conversion & C++ Casting Operators
│   ├── ex00/        # Scalar Converter (implicit/explicit string parsing to primitive types)
│   ├── ex01/        # Serializer (reinterpret_cast uintptr_t conversion)
│   └── ex02/        # Type Identifier (dynamic_cast and reference-based RTTI)
│
├── cpp_07/          # Function & Class Templates
│   ├── ex00/        # Generic swap, min, and max function templates
│   ├── ex01/        # Iterative iter function template
│   └── ex02/        # Generic Array class template with bounds checking
│
├── cpp_08/          # STL Containers, Iterators, and Algorithms
│   ├── ex00/        # Easyfind template algorithm for sequential containers
│   ├── ex01/        # Span class for tracking min/max distances across ranges
│   └── ex02/        # MutantStack (making std::stack iterable)
│
└── cpp_09/          # Practical STL Container Applications
    ├── ex00/        # Bitcoin Exchange (std::map evaluation engine)
    ├── ex01/        # Reverse Polish Notation (std::stack expression parser)
    └── ex02/        # PmergeMe (Ford-Johnson merge-insert sort with std::deque/std::vector)
[TRIPLE_BACKTICKS]

---

## 🧠 Technical Highlight: The Orthodox Canonical Class Form

Starting in `cpp_02`, every instantiated class adheres to the **Orthodox Canonical Form**. This ensures robust resource management, safe object copying, and memory leak prevention across complex class hierarchies:

[TRIPLE_BACKTICKS]cpp
class CanonicalClass {
public:
    CanonicalClass();                                      // Default Constructor
    CanonicalClass(const CanonicalClass &src);             // Copy Constructor
    CanonicalClass &operator=(const CanonicalClass &rhs);  // Copy Assignment Operator
    ~CanonicalClass();                                     // Destructor
};
[TRIPLE_BACKTICKS]

---

## 🚀 Compilation & Execution

Each exercise directory contains its own self-contained `Makefile` enforcing strict compilation flags.

### Building an Exercise

Navigate to the target exercise directory within any module and run `make`:

[TRIPLE_BACKTICKS]bash
cd cpp_01/ex00
make
./zombie
[TRIPLE_BACKTICKS]

### Common Makefile Rules

*   `make`: Compiles the executable using `c++ -Wall -Wextra -Werror -std=c++98`.
*   `make clean`: Removes object files (`.o`).
*   `make fclean`: Removes object files and the generated executable binary.
*   `make re`: Recompiles the exercise from scratch.

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
