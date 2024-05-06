### How to contribute to this project

1. Clone the Project :

Start by cloning the GitHub project on your local machine using the git clone command 

```
git clone https://github.com/Sbleut/todolist.git
```

2. Créez une Branche :

Before you start work, create a new branch for your contribution using an appropriate naming convention. For example, if you're working on a new feature, name your branch descriptively.

```
git checkout -b new-function
```

3. Make your Modifications :

Make your changes to the code, making sure that you follow the project's coding standards and that you comment on your work so that it is easier to understand.

4. Commit et Push :

- Once you have completed your changes, add your modified files to the index using the git add command, then perform a commit with a descriptive message to explain your changes.

```
git add .
git commit -m "Description of changes made"
```

- Then push your branch to the remote repository on GitHub.
```
git push origin nouvelle-fonctionnalite
```

5. Open a Pull Request (PR) :

- Go to the project's GitHub page and open a new Pull Request from your branch to the project's main branch. Describe your changes and add relevant details to help reviewers understand your contribution.

6. Review and feedback:

- Project collaborators will review your Pull Request, possibly asking questions or requesting additional changes. Make sure you respond to any comments and update your PR accordingly.

7. Merge the Pull Request:

- Once your Pull Request has been approved, it can be merged into the main branch of the project. Make sure all changes have been incorporated correctly before merging.

8. Clean up:

- Once the merge is complete, you can delete your local and remote branch if necessary.

```
git checkout main
git branch -d new-function
git push origin --delete new-function
```

Quality process to respect in order ensure code efficiency.