# Project Rules - HostPN Plugin

## Forms
- Always use `HOSTPN_Forms` class (`hostpn_input_wrapper_builder`) for building ALL form fields. Never use raw HTML inputs, textareas, or selects.

## File placement
- The `.claude` folder must always be at the repo root level (`hostpn/.claude`), never inside `trunk/`. This prevents it from being uploaded to the server.
- NEVER create a file called `nul`. It is a reserved device name on Windows and breaks git completely. If one exists, delete it immediately.
