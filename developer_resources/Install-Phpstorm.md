# PHPStorm Installation
This guide details how to install PHPStorm on Ubuntu for use with this project. This guide is about Linux only, if you
want to use Windows, you're on your own.

## 1. Create a JetBrains account
Visit https://account.jetbrains.com/login and create an account with your student e-mail. Go to the Apply for a
License page and choose to apply for a student license. You will need to verify your student email again.

## 2. Download the PHPStorm Application
1. Download from https://www.jetbrains.com/phpstorm/download
2. Extract the archive file with `tar -xvf PhpStorm-2017.3.4.tar.gz`
3. Move the contents of the resulting folder somewhere sensible. To keep with Linux convention:
~/.local/opt/jetbrains/phpstorm to install for your user only, or /opt/phpstorm to install system-wide. I recommend the
first option if you don't have multiple users on your system, because it will make auto-updates easier.
   ```bash
    mkdir -p ~/.local/opt/jetbrains/phpstorm
    cp -r PhpStorm-173.4548.32/* ~/.local/opt/jetbrains/phpstorm/
   ```
4. Launch PHPStorm with the script in bin/phpstorm.sh relative to where you extracted it.
   ```bash
   ~/.local/opt/jetbrains/phpstorm/bin/phpstorm.sh
   ```

5. Walk through the JetBrains installer. When asked to provide your license information, choose to log in to your
JetBrains account and log in with the information you created earlier. You will also want to choose to create a desktop
launcher, this will allow you to launch PHPStorm from your graphical shell's menu.

## 3. Add your SSH key to GitHub
Generate a new keypair
```bash
ssh-keygen -q -t rsa -N "" -f ~/.ssh/id_rsa 
```
Print the public key to the terminal
```bash
cat ~/.ssh/id_rsa.pub 
```
Go to https://github.com/settings/keys and click new SSH key. Paste the output of the previous command. 

## 4. Clone the Repository
1. On the Welcome screen, select "Check out from Version Control" and choose "Git" (NOT "GitHub")
2. Paste the repository URL: `git@github.com:AnthonyD1/Capstone2018.git`
3. You may choose a different local folder to clone the repository to if you wish, but you don't have to.

## 5. Install PHP Binaries
We are using PHP 7.0
```bash
sudo apt install php7.0-cli php7.0-cgi php7.0-intl php7.0-sqlite3
```

## 6. Running the sample program
You should now be set up to see the result of your PHP programs in the browser.
1. Open the `developer_resources/test_program.php` file.
2. Move your cursor to the upper right of the editor area. A small toolbar with web browser icons will appear. Click
your preferred browser.
3. The page will open in the browser. You should see "Hello, World" in big letters.
