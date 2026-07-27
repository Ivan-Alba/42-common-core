# ft_irc — Internet Relay Chat Server (C++98)

<p align="center">
  <img src="https://img.shields.io/badge/Project-ft__irc-000000?style=for-the-badge&logo=42&logoColor=white" alt="ft_irc" />
  <img src="https://img.shields.io/badge/Language-C%2B%2B98-00599C?style=for-the-badge&logo=cplusplus&logoColor=white" alt="C++98" />
  <img src="https://img.shields.io/badge/Networking-TCP%2FIP%20Sockets%20%7C%20poll()-FF6F00?style=for-the-badge" alt="Sockets & poll()" />
  <img src="https://img.shields.io/badge/Protocol-RFC%201459%20%2F%202812-0071C5?style=for-the-badge" alt="RFC 1459/2812" />
  <img src="https://img.shields.io/badge/Grade-125%20%2F%20100-success?style=for-the-badge" alt="125/100" />
</p>

---

<table align="center">
  <tr>
    <td align="center" width="50%">
      <img src="./README_assets/irc_client_chat.png" width="100%" alt="IRC Client Connection & Channel Chat" />
      <i>IRSSI / LimeChat Client Connection & Real-Time Messaging</i>
    </td>
    <td align="center" width="50%">
      <img src="./README_assets/botserv_interaction.png" width="100%" alt="BotServ Automated Interactive Bot" />
      <i>Bonus Feature: Interactive Channel BotServ (!help, !roll, !choose)</i>
    </td>
  </tr>
</table>

> **Note:** *The images above are placeholders. You can place your custom terminal execution logs or IRC client screenshots (e.g., IRSSI, LimeChat, HexChat) in the `README_assets/` directory.*

---

## 📖 Overview

**ft_irc** is a fully compliant, real-time **Internet Relay Chat (IRC) server** written in C++98, designed according to the specifications defined in **RFC 1459** and **RFC 2812**.

The core engine operates a single-threaded, non-blocking network architecture utilizing the `poll()` system call. It handles concurrent TCP client connections, authenticates credentials, parses complex command tokens, manages channel life cycles with operator privileges, and broadcasts messages across isolated channels or private peer-to-peer streams—all without incurring race conditions or using forbidden multithreading libraries.

As an extended feature (**Bonus: 125/125**), the server embeds an automated IRC Bot (`BotServ`) initialized directly into a `#welcome` channel, capable of responding to user triggers, greeting newcomers, and executing utility commands in real time.

---

## 📋 Key Technical Highlights & RFC Standards

*   **Non-Blocking I/O Multiplexing**: Built entirely around a non-blocking `poll()` event loop. Socket state mutations (`O_NONBLOCK` via `fcntl`) ensure zero read/write blocking across listening and client sockets.
*   **Asynchronous Outbound Buffering**: Messages sent to congested clients are queued inside outbound buffers (`bufferOut`). Sockets are dynamically flagged with `POLLOUT` until pending data streams are completely flushed.
*   **Full Authentication Workflow**: Strict `PASS` $\rightarrow$ `NICK` $\rightarrow$ `USER` state machine validation before granting access to network resources and channel operations.
*   **Comprehensive Channel Privileges**: Support for channel operators (`@`), kick mechanisms (`KICK`), topic control (`TOPIC`), user invitations (`INVITE`), and granular mode alterations (`MODE`).
*   **Virtual Bot Architecture (`BotServ`)**: Clean OOP extension where the bot is registered as a virtual `Client` object with `fd = -1`, seamlessly interacting with internal message handlers without triggering network socket operations.

---

## 🧠 Server Architecture & Event Loop Topology

The diagram below outlines the asynchronous event loop driving socket acceptance, buffer reading, command dispatching, and output queue flushing:

<div align="center">
<pre>
                        +----------------------------+
                        |  TCP Listener (listenFd)   |
                        +--------------+-------------+
                                       |
                                 poll() Loop
                                       |
           +---------------------------+---------------------------+
           |                                                       |
   [ POLLIN Event ]                                        [ POLLOUT Event ]
           |                                                       |
  +--------v--------+                                     +--------v--------+
  |  fd == listenFd |                                     | Pending Outbound|
  +--------+--------+                                     |  Buffer Data?   |
           |                                              +--------+--------+
    acceptNewClient()                                              |
           |                                              sendPendingMessages()
  +--------v--------+                                              |
  |  Client Sockets |                                     Clear POLLOUT Flag
  +--------+--------+                                     when Buffer Empty
           |
 recv() -> appendToBuffer()
           |
 ClientMessageHandler::handleMessage()
           |
  +--------v-----------------------------------+
  |  Tokenizer & Command Map Dispatcher        |
  |  - PASS, NICK, USER                        |
  |  - JOIN, PART, KICK, INVITE, TOPIC, MODE   |
  |  - PRIVMSG, NOTICE, PING/PONG              |
  +--------+-----------------------------------+
           |
           +---------------------> BotServ Trigger Check (If target is Bot)
</pre>
</div>

---

## 🛠️ Repository Directory Structure

[TRIPLE_BACKTICKS]text
ft_irc/
├── Makefile                # Compilation rules (-Wall -Wextra -Werror -std=c++98 -fsanitize=address)
└── src/
    ├── main.cpp            # Entry point, argument validation (port & password), server loop trigger
    ├── Server.hpp          # Core Server class definition, socket setup, and pollfd vector management
    ├── Server.cpp          # Non-blocking socket lifecycle, poll() event loop, send/recv mechanics
    ├── Client.hpp          # Client identity state (nick, user, hostname, auth status, buffers)
    ├── Client.cpp          # Client attribute setters/getters and buffer manipulation
    ├── Channel.hpp         # Channel entity state (users, operators, invited list, modes, topic)
    ├── Channel.cpp         # User registration, operator promotion, and mode flags (+i, +t, +k, +o, +l)
    ├── ClientMessageHandler.hpp # Static Command Map function pointers & ModeContext definitions
    ├── ClientMessageHandler.cpp # Tokenizer (\r\n handling), command parser, and IRC protocol handlers
    ├── Bot.hpp             # Virtual Bot identity and command dispatching declarations
    ├── Bot.cpp             # Bonus Bot implementation (!help, !echo, !roll, !choose, !uptime)
    ├── IRCReplies.hpp      # RFC 1459/2812 numeric response macros (RPL_*, ERR_*)
    ├── Utils.hpp           # String trimming, splitting, and conversion helper routines
    ├── Utils.cpp           # Vector splitting and string stream utility implementations
    └── config.hpp          # Network constants (AF_INET, SOCK_STREAM, SO_REUSEADDR, BUFFER_SIZE)
[TRIPLE_BACKTICKS]

---

## ⚙️ Implemented IRC Commands & Channel Modes

### Core Protocol Commands

| Command | Syntax | Operational Description |
| :--- | :--- | :--- |
| **`PASS`** | `PASS <password>` | Authenticates the connection against the server secret. |
| **`NICK`** | `NICK <nickname>` | Assigns or changes a unique client nickname on the network. |
| **`USER`** | `USER <username> <hostname> <servername> <realname>` | Specifies client connection metadata. |
| **`JOIN`** | `JOIN <channel>[,<chan_list>] [<key>[,<key_list>]]` | Joins or creates target channels; verifies modes (`+k`, `+i`, `+l`). |
| **`PART`** | `PART <channel> [:<reason>]` | Leaves one or multiple channels. |
| **`PRIVMSG`**| `PRIVMSG <target> :<message>` | Sends a private message to a channel or specific nickname. |
| **`NOTICE`** | `NOTICE <target> :<message>` | Sends notices without triggering automated error responses. |
| **`KICK`** | `KICK <channel> <user> [:<reason>]` | Removes a user from a channel (Operator privilege required). |
| **`INVITE`**| `INVITE <user> <channel>` | Invites a target user to an invite-only channel (`+i`). |
| **`TOPIC`** | `TOPIC <channel> [:<topic>]` | Views or sets the channel topic (`+t` operator lock enforced). |
| **`MODE`**  | `MODE <channel> {[+|-]|i|t|k|o|l} [<params>]` | Configures channel authorization and limitation flags. |
| **`PING`**  | `PING <token>` | Health check request; returns `PONG :<token>`. |
| **`QUIT`**  | `QUIT [:<reason>]` | Closes socket session and removes client from all joined channels. |

### Supported Channel Modes (`MODE`)

*   **`i` (Invite-Only)**: Channel can only be joined if invited via the `INVITE` command.
*   **`t` (Topic Lock)**: Topic modifications restricted exclusively to channel operators (`+o`).
*   **`k` (Channel Key)**: Sets a mandatory passphrase required to join the channel (`JOIN #channel password`).
*   **`o` (Operator Privilege)**: Grants or revokes channel operator status (`@`) to a specified nickname.
*   **`l` (User Limit)**: Restricts maximum user capacity within the channel (`ERR_CHANNELISFULL`).

---

## 🤖 Bonus Feature: Interactive `BotServ`

The server automatically instantiates an integrated bot named `BotServ` upon launch and registers it into the default `#welcome` channel.

### Bot Characteristics & Commands
*   **Automatic Welcome**: Sends a greeting message whenever a new user joins a channel where `BotServ` is present.
*   **Invite-Responsive**: Automatically joins any channel when invited using `/invite BotServ #channel`.
*   **Command Triggers**:
    *   `!help` — Displays available bot commands.
    *   `!echo <msg>` — Repeats input text back to the channel.
    *   `!roll` — Generates a random dice roll (1 to 6).
    *   `!choose optionA|optionB|optionC` — Randomly selects one choice from a pipe-separated list.
    *   `!uptime` — Displays current uptime duration for the active session.

---

## 🚀 Compilation & Execution

### Build Instructions

Compile the `ircserv` executable binary using the included `Makefile`:

[TRIPLE_BACKTICKS]bash
make
[TRIPLE_BACKTICKS]

*The build process applies `-Wall -Wextra -Werror -std=c++98 -fsanitize=address -g` to guarantee strict memory safety and C++98 compliance.*

### Running the Server

Launch the server by supplying a target port number and connection password:

[TRIPLE_BACKTICKS]bash
./ircserv <port> <password>
[TRIPLE_BACKTICKS]

**Example:**
[TRIPLE_BACKTICKS]bash
./ircserv 6667 mysecretpassword
[TRIPLE_BACKTICKS]

### Connecting via IRC Client (IRSSI Example)

Connect to your local server instance using `irssi` or any standard IRC client:

[TRIPLE_BACKTICKS]bash
irssi
[TRIPLE_BACKTICKS]

Inside the `irssi` interface, run:

[TRIPLE_BACKTICKS]text
/connect localhost 6667 mysecretpassword mynickname
/join #welcome
[TRIPLE_BACKTICKS]

---

<div align="center">
  <p>Developed as part of the 42 School Curriculum.</p>
</div>
