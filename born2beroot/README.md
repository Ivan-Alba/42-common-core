# Born2beroot — System Administration and Server Virtualization

<p align="center">
  <img src="https://img.shields.io/badge/Project-Born2beroot-000000?style=for-the-badge&logo=42&logoColor=white" alt="Born2beroot" />
  <img src="https://img.shields.io/badge/OS-Debian-A81D33?style=for-the-badge&logo=debian&logoColor=white" alt="Debian" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📋 Project Overview

This project focuses on the installation, configuration, and secure administration of a standalone, non-graphical server using **Debian** inside a virtualized environment. It introduces core systems engineering concepts such as storage partitioning via **LVM (Logical Volume Manager)**, strict access control tracking, network filtering with a firewall, and basic automated shells.

> This repository hosts exclusively the validation `signature.txt` file containing the virtual disk's unique cryptographic signature and the automated monitoring shell script required by the 42 evaluation curriculum.

---

## 🛠️ System Specifications & Requirements

### Security Policies

*   **Sudo Configuration**: Strict tracking mechanics restricting execution limits to 3 validation attempts. It displays a custom password warning header, enables strict TTY environments, isolates binary paths, and logs entries inside `/var/log/sudo/`.
*   **Password Quality Controls**: Enforced using `libpam-pwquality` requiring a minimum length of 10 characters, mixed casing, integers, strict maximum repetition thresholds ($\le$ 3 consecutive identical characters), and a 30-day expiration lifetime policy.
*   **Network Filtering (UFW)**: Firewalled perimeter blocking all traffic by default. Only port **4242** (SSH), port **80** (HTTP), and port **443** (HTTPS) are exposed. Root login over SSH is explicitly forbidden.

### Bonus Storage Partitioning Layout (LVM + LUKS Encryption)

The full storage layout uses an encrypted physical partition boundary (`sda5_crypt`) containing the following Logical Volume Manager mappings:

| Logical Volume Name | Mount Point | Size | Description |
| :--- | :--- | :--- | :--- |
| `LVMGroup-root` | `/` | 10G | Main operational system root filesystem boundary. |
| `LVMGroup-swap` | `[SWAP]` | 2.3G | Volatile storage used when physical RAM limits clear out. |
| `LVMGroup-home` | `/home` | 5G | Isolated space dedicated to non-root user file persistence. |
| `LVMGroup-var` | `/var` | 3G | Dynamic application caching and runtime variables. |
| `LVMGroup-srv` | `/srv` | 3G | Dedicated site data isolation for web services. |
| `LVMGroup-tmp` | `/tmp` | 3G | Temporary scratch space auto-wiped during reboots. |
| `LVMGroup-var--log` | `/var/log` | 4G | Hardened containment space for system and sudo logs. |

---

## 🌟 Bonus Features Implementation

The server is fully provisioned with a secure, production-ready web stack architecture:

*   **Functional WordPress Site**: Powered by a lightweight **Lighttpd** engine working alongside a localized **MariaDB** SQL container and **PHP** processors.
*   **Active Intrusion Defense (Fail2ban)**: Monitors authorization failures over the network, automatically adding malicious actors to the `ufw` blocklist if brute-force attempts target port **4242**.

---

## 🚀 Cheat Sheet: Defense & Evaluation Commands

Essential system commands utilized throughout the practical evaluation sequence to verify server integrity.

### 1. Service Management & Status Tracking

To inspect the live operation parameters of the security firewall, web services, database, and system entry monitors:

    sudo ufw status numbered
    sudo systemctl status ssh
    sudo systemctl status lighttpd
    sudo systemctl status mariadb
    sudo systemctl status fail2ban

### 2. User & Group Management Operations

To construct or assign permissions to academic infrastructure groups during user evaluation stages:

    sudo adduser new_username
    sudo addgroup evaluation
    sudo adduser username evaluation

### 3. Verification of System Architecture

To list the structural allocation of LVM boundaries, partitions, and user grouping flags:

    lsblk
    getent group evaluation

---

## 📊 Automated Monitoring Script & Cron Integration

A foundational system daemon utility designed to run periodically via system **Cron jobs (`cron daemon`)**. It is configured within the root crontab to execute every 10 minutes from server boot, broadcasting critical telemetry snapshots across all open user terminal frames simultaneously using the `wall` command.

```bash
#!/bin/bash
# System tracking snapshot template
arch=$(uname -a)
cpup=$(lscpu | grep "CPU(s):" | head -n 1 | awk '{print $2}')
vcpu=$(cat /proc/cpuinfo | grep processor | wc -l)
fram=$(free -m | awk '/Mem:/ {printf "%d/%dMB (%.2f%%)", $3, $2, $3/$2*100}')
fdisk=$(df -Bg | grep '^/dev/' | grep -v '/boot$' | awk '{ut += $3; tt += $2} END {printf "%d/%dGb (%d%%)", ut, tt, ut/tt*100}')
cpul=$(top -bn1 | grep '^%Cpu' | cut -c 9- | awk '{printf "%.1f%%", $1 + $3}')
lbboot=$(who -b | awk '{print $3 " " $4}')
lvmu=$(if [ $(lsblk | grep "lvm" | wc -l) -gt 0 ]; then echo yes; else echo no; fi)
ctcp=$(ss -ta | grep ESTAB | wc -l)
ulog=$(users | wc -w)
cnet=$(ip link show | grep "ether" | awk '{print $2}' | head -n 1)
csudo=$(journalctl _COMM=sudo | grep COMMAND | wc -l)

wall "  #Architecture: $arch
        #CPU physical: $cpup
        #vCPU: $vcpu
        #Memory Usage: $fram
        #Disk Usage: $fdisk
        #CPU load: $cpul
        #Last boot: $lbboot
        #LVM use: $lvmu
        #Connections TCP: $ctcp ESTABLISHED
        #User log: $ulog
        #Network: IP $cnet
        #Sudo: $csudo cmd"
```
