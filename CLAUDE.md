# Project Rules - HostPN Plugin

## Forms
- Always use `HOSTPN_Forms` class (`hostpn_input_wrapper_builder`) for building ALL form fields. Never use raw HTML inputs, textareas, or selects.

## File placement
- The `.claude` folder must always be at the repo root level (`hostpn/.claude`), never inside `trunk/`. This prevents it from being uploaded to the server.
- The `nul` file must always be at the repo root level (`hostpn/nul`), never inside `trunk/`. It breaks the SVN upload if placed inside trunk.
