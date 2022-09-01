# violinist-messages

[![Test](https://github.com/violinist-dev/violinist-messages/actions/workflows/test.yml/badge.svg)](https://github.com/violinist-dev/violinist-messages/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/violinist-dev/violinist-messages/badge.svg?branch=master)](https://coveralls.io/github/violinist-dev/violinist-messages?branch=master)

Template(s) for the pull requests that we send on updates.

## Preview

To see a preview of how they look, head to [https://messages.violinist.io](https://messages.violinist.io)

## Templates

### Template for the pull request title

Currently it receives the following variables:

`name`: The name of the package to update.

`current_version`: The currently installed version of a package.

`new_version`: The version of the package the pull request is updating to.

`security_prefix`: Either the string `[SECURITY]` or an empty string, depending on whether the update was a security update or not.

### Template for the pull request body

Currently it receives the following variables:

`title`: The title rendered from the title template.

`changelog`: A markdown formatted changelog list, if available.

`custom_message`: A custom message, if configured.

`updated_list`: A list of the changed packages in the update.

`package`: The name of the package being updated.

`changed_files`: An array of the files changed in the update, if available.
