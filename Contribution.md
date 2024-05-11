### How to contribute to this project

1. Clone the Project :

Start by cloning the GitHub project on your local machine using the git clone command 

```
git clone https://github.com/Sbleut/todolist.git
```

2. Créez une Branche :

Before you start work, create a new branch for your contribution using an appropriate naming convention. For example, if you're working on a new feature, name your branch descriptively.

```
git checkout -b new-feature
```

3. Make your Modifications :

Make your changes to the code, making sure that you follow the project's coding standards and that you comment on your work so that it is easier to understand.

4. Commit et Push :

Once you have completed your changes, add your modified files to the index using the git add command, then perform a commit with a descriptive message to explain your changes.

```
git add .
git commit -m "Description of changes made"
```

Then push your branch to the remote repository on GitHub.
```
git push origin new-feature
```

5. Tests Writting Codacy

Write tests in the test folder for your code and check that it's at least 70% coverage with phpunit.
Check taht your code is atleast B in codacy notation chart.
    
Add to your .env.test with this line
```
    DATABASE_URL="mysql://root:@127.0.0.1:3306/todolist-test?serverVersion=5.7.36&charset=utf8mb4"
```
Create your test DB:
```
    symfony console d:d:c --env=test
    symfony console d:s:c --env=test
```
Create and Load Fixtures to testdb
```
    symfony console d:f:l --env=test
```
Run this command to launch test session on test DB
```
    php bin/phpunit --coverage-html web/test-coverage
```

6. Open a Pull Request (PR) :

Go to the project's GitHub page and open a new Pull Request from your branch to the project's main branch. Describe your changes and add relevant details to help reviewers understand your contribution.

7. Review and feedback:

 Project collaborators will review your Pull Request, possibly asking questions or requesting additional changes. Make sure you respond to any comments and update your PR accordingly.

8. Our team Merge your Pull Request:

Once your Pull Request has been approved, our team will merged your branch into the main branch. Make sure all changes have been incorporated correctly.

8. Clean up:

Once the merge is complete, you can delete your local and remote branch if necessary.

```
git checkout main
git branch -d new-feature
git push origin --delete new-feature
```

Quality process to respect in order ensure code efficiency.