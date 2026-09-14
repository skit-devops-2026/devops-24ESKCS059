pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('PHP Syntax Check') {
            steps {
                bat 'C:\\xampp\\php\\php.exe -l index.php'
            }
        }

        stage('Run Tests') {
            steps {
                bat 'C:\\xampp\\php\\php.exe tests\\test_project.php'
            }
        }
    }
}