# NetPractice — Network Configuration & Subnetting

<p align="center">
  <img src="https://img.shields.io/badge/Project-NetPractice-000000?style=for-the-badge&logo=42&logoColor=white" alt="NetPractice" />
  <img src="https://img.shields.io/badge/Topic-Networking-00599C?style=for-the-badge&logo=cisco&logoColor=white" alt="Networking" />
  <img src="https://img.shields.io/badge/Environment-Web_Simulator-FF6F00?style=for-the-badge&logo=html5&logoColor=white" alt="Web Simulator" />
  <img src="https://img.shields.io/badge/Grade-100%20%2F%20100-success?style=for-the-badge" alt="100/100" />
</p>

---

<table align="center">
  <tr>
    <td align="center" width="50%">
      <img src="./README_assets/network_topology.png" width="100%" alt="Network Topology Simulation" />
      <i>Network Topology (Hosts, Switches, and Routers)</i>
    </td>
    <td align="center" width="50%">
      <img src="./README_assets/routing_table.png" width="100%" alt="Routing Table Configuration" />
      <i>Routing Table Resolution</i>
    </td>
  </tr>
</table>

> **Note:** *The images above are placeholders. If you have screenshots of your own solved levels, store them in the `README_assets/` directory and update the relative paths above.*

---

## 📖 Overview

**NetPractice** is a theoretical and practical networking problem-solving project. Unlike traditional 42 C programming projects, NetPractice does not involve writing source code. Instead, it provides a web-based network simulator where the primary objective is to configure network interfaces to establish successful communication across various devices.

Through 10 levels of increasing complexity, the project requires a solid understanding of **IPv4** addressing, subnet mask calculation using **CIDR** (Classless Inter-Domain Routing) notation, and **Routing Table** configuration to allow data packets to traverse isolated networks seamlessly.

---

## 📋 Key Concepts & Specifications

Although no codebase is generated, validating the project requires a firm grasp of essential telecommunication principles:

*   **IPv4 Addressing & Subnetting (CIDR)**: Calculating network addresses, broadcast addresses, and usable host IP ranges based on the subnet mask (e.g., `/24`, `/28`, etc.).
*   **Routers vs. Switches**: Understanding collision and broadcast domain isolation. Switches connect devices within the same local subnet, whereas routers interconnect distinct subnets.
*   **Routing Tables**: Defining the destination network, mask, and next hop (Gateway) so devices know where to forward traffic meant for external networks.
*   **Default Gateways**: Configuring `0.0.0.0/0` routes to forward unmatched traffic toward external networks or the internet.

---

## 🧠 Network Architecture (Structural Example)

To illustrate the underlying routing challenges, here is a simplified network routing scheme implemented in the higher simulator levels:

<div align="center">
<pre>
   [ Net A: 192.168.1.0/26 ]                             [ Net B: 10.0.0.0/30 ]
   Range: .1 - .62                                       Range: .1 - .2

+-------------+                                                     +-------------+
|   Host A    |  192.168.1.1                 10.0.0.1  10.0.0.2     |   Host B    |
| GW: .1.62   |-------+ (Switch) +-------[ Router1 ]-----[ Router2 ]| GW: .0.1    |
+-------------+      192.168.1.62                                   +-------------+
                                                                         |
                                                                         |
                                                                  [ INTERNET ]
                                                                  (0.0.0.0/0)
</pre>
</div>

*In this scenario, for `Host A` to communicate with `Host B`, the routing table on `Router 1` must explicitly indicate that the `10.0.0.0/30` network is reachable through its connected interface on `Router 2`.*

---

## 🎮 Simulator Levels

The assignment is divided into **10 procedural levels** (IP addresses change dynamically on each attempt, preventing hardcoded solutions):

1.  **Levels 1–3**: Basic IP addressing within the same subnet, direct node connections, and unmanaged switches.
2.  **Levels 4–6**: Introduction to subnetting using complex CIDR masks and network capacity calculations to prevent IP overlapping.
3.  **Levels 7–8**: Router configuration, isolated network segregation, and Default Gateway assignments.
4.  **Levels 9–10**: Complete enterprise topologies featuring multiple routers, cross-routing tables, and Internet routing (`0.0.0.0/0`).

---

## 🚀 Usage & Execution

Because the simulator runs in a self-contained web environment, executing the project simply requires launching the web interface.

### Prerequisites
No compilers or system libraries are required. Only a modern web browser is needed.

### Instructions
1. Clone or download this repository.
2. Extract the provided simulator archive.
3. Open the `index.html` file in any web browser:

[TRIPLE_BACKTICKS]bash
# Example command for Linux / macOS
open index.html
[TRIPLE_BACKTICKS]

4. Select a level, calculate the necessary IP ranges, and input the correct configuration across all device nodes until connection tests (*pings*) pass successfully.

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
