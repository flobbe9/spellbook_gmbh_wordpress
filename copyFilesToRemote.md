Specify files to copy from git to remote servers /root folder

1. add file name to list in "pipeline.main.yml" at the appleboy/scp-action after "source:" (comma separated, no white space).
   Dont prepend "/" or "./", execution folder is always the repository root (same level as .git folder)
2. Optionally add execute permission to file at the next step (e.g. for .sh files). Simply add the file name to the list (separated whith white space)