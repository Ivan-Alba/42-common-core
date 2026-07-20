# Born2beroot — System Administration and Server Virtualization

<p align="center">
  <img src="https://img.shields.io/badge/Project-Born2beroot-000000?style=for-the-badge&logo=42&logoColor=white" alt="Born2beroot" />
  <img src="https://img.shields.io/badge/OS-Debian-A81D33?style=for-the-badge&logo=debian&logoColor=white" alt="Debian" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

## 📋 Project Overview

This project focuses on the installation, configuration, and secure administration of a standalone server using **Debian** inside a virtualized environment. It introduces core systems engineering concepts such as storage partitioning via **LVM (Logical Volume Manager)**, strict access control tracking, network filtering with a firewall, and basic automated shells.

> This repository hosts exclusively the validation `signature.txt` file and the automated monitoring shell script required by the 42 evaluation curriculum.

---

## 🛠️ System Specifications & Requirements

### Security Policies

*   **Sudo Configuration**: Strict tracking mechanics restricting execution limits, keeping dedicated TTY records, custom warning headers, and a strict log path at `/var/log/sudo/`.
*   **Password Quality Controls**: Enforced using `libpam-pwquality` requiring a minimum length of 10 characters, mixed casing, integers, strict maximum repetition thresholds, and a 30-day expiration lifetime policy.
*   **Network Filtering (UFW)**: Firewalled perimeter blocking all traffic by default, opening port **4242** for custom SSH tunnels and port **80** / **443** for secure web traffic features.

### Mandatory & Bonus Partitioning Layout (LVM)

The layout isolates systems partitions into separate physical-backed boundaries using logical structures, expanding space allocations to support the database and web infrastructure securely:

| Partition Target | Logical Volume Name | Size | Description |
| :--- | :--- | :--- | :--- |
| `/` | `root` | ~10G | Main operational system root boundary. |
| `/home` | `home` | ~5G | Dedicated volume isolating specific user files. |
| `/var` | `var` | ~4G | Dynamic logs and variable caching data storage. |
| `/tmp` | `tmp` | ~2G | Volatile scratch space automatically wiped on boot. |
| `/var/log` | `log` | ~2G | Dedicated, isolated containment field for system logs. |

---

## 🌟 Bonus Features Implementation

The server is fully provisioned with a secure, production-ready **LNMP/LLMP Stack** alongside automated active defense systems:

### 1. Secure Web Server Infrastructure
*   **Web Daemon**: Implemented via a lightweight web server engine (**Lighttpd** or **Nginx**) running securely on standard HTTP/HTTPS channels.
*   **Database Management System**: Structured using **MariaDB (MySQL)**, fully hardened using `mysql_secure_installation` routines to restrict remote root entry points.
*   **Dynamic Processing**: Native support for **PHP-FPM** processors communicating directly via UNIX sockets to restrict unauthorized port exposure.

### 2. Active Defense Integration (Fail2ban)
*   Monitors systemic authorization failures inside the local environment.
*   Automatically drops adversarial source nodes at the `ufw` layer if brute-force thresholds are triggered on port **4242**.

---

## 🚀 Cheat Sheet: Defense & Evaluation Commands

Essential system commands utilized throughout the practical evaluation sequence to verify server integrity.

### 1. Service Management & Status Tracking

To inspect the live operation parameters of the security firewall, web services, database, and system entry monitors:

    sudo ufw status numbered
    sudo systemctl status ssh
    sudo systemctl status lighttpd  # or nginx
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
