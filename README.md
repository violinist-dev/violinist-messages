# violinist-messages
Template(s) for the pull requests that we send on updates.

## Templates

### Template for the pull request title

Currently it receives the following variables:

`name`: The name of the package to update.
`current_version`: The currently installed version of a package.
`new_version`: The version of the package the pull request is updating to.

### Template for the pull request body

Currently it receives the following variables:

`title`: The title rendered from the title template.
`changelog` A markdown formatted changelog list, if available.
