# Git Usage

[Back](README.MD)

The development of FoldingCoin is based loosely around the GitFlow model. Please refer
to [https://datasift.github.io/gitflow/IntroducingGitFlow.html](https://datasift.github.io/gitflow/IntroducingGitFlow.html)
for some background information.

## Running Git

There are a few important notes on the usage of Git.

#### Always Run Git From The Host Machine

You should not run git from within the virtual machines SSH shell. There are a couple of
reason for this. Firstly, it is going to be considerably slower. Secondly and most
importantly, the virtual machine will be frequently destroyed/reprovisioned, and each
time this happens all of your Git settings will be lost.

#### Always Run Git From The Project Root

When on the commandline, always make sure you run Git from the root of the project (the
same folder this README file is located in). If you run it from within one of the
subfolders you may inadvertently miss some files when creating your commits.

## Review Process

All code generated as part of the project must go through a peer review process prior to
being merged into the core product. As such there is a process that must be followed

### Step One: Creating New Branch

From the root of the project execute the following commands.

```
cd /vagrant
git checkout master 
git checkout -b XXX-YYY/FLDC-ZZZ
```

The first command will make sure you are on the `master` branch of the project.

The second command will create a new branch on your local computer. The structure of the 
branch name is as follows:

XXX - This is the type of branch, from the list below:

* hotfix - The branch is addressing a critical issue identified in production code
* task - The branch is developing code associated with a Taiga task

YYY - This is the root branch from which this one is based

* master - This is the current production branch
* develop - This is the current feature/development branch

ZZZ - This is the Taiga task/issue number to which the code related

For example:

```
cd /vagrant
git checkout master 
git checkout -b hotfix-master/FLDC-1142
```

`hotfix-master/FLDC-1142` would be a critical issue fix on the master branch relating to
Taiga ticket number 1142.

```
cd /vagrant
git checkout master 
git checkout -b task-develop/FLDC-5222
```

`task-develop/FLDC-5222` would be a development task on the current feature development
branch relating to Taiga ticket number 5222.

### Step Two: Write Code

### Step Three: Add Commits

As you develop the code add regular commits to your branch. Not only does this make
it possible for other developers to follow your thought process throughout the development
but it also allows you to snapshot your development in the event that you make a
mistake, or a fundamental change in your thought process as you develop.

To add a commit simply execute the following commands.

```
cd /vagrant
git add .
git commit -m "Explanation of the change"
```

The first command adds all files changed to the commit. You can alternatively add
single/groups of files as such

```
cd /vagrant
git add README.MD
git add laravel/README_COMPOSER.MD
git commit -m "Updating documentation"
git add laravel/app/Services/ConfigLoaderServer
git commit -m "Creating config loader framework"
```

### Step Four: Push To Origin

Once you have finished coding and are ready for the code to be reviewed, push the changes
to your origin branch. The origin branch is your fork of the FoldingCoin repository on
your personal Github account.

```
cd /vagrant
git push origin HEAD
```

### Step Five: Create Pull Request (PR)

Login to your Github account and open your fork of the FoldingCoin repository. If you
just pushed the branch you will likely see a popup message at the top of the page
with the branch you just pushed, and a button labelled "New Pull Request". 

Simply click on this button, give the pull request a describe title including the Taiga 
ticket number and the branch on which the change has been based

e.g. "Create config loader service [5222] (master)"

Before submitting the PR make sure that you are lodging it against the correct branch
using the drop-down boxes at the top of the form. If the page shows that you have 
made changes that you know you didn't it is most likely a result of you attempting to
lodge your PR against the wrong branch.
