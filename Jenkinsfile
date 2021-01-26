pipeline {
    agent any

    environment {
        JENKINS_HOME_HOST = '/home/ubuntu/jenkins-docker-compose/jenkins_home'
    }

    stages {
        stage('Test') {
            steps {
                sh './test.sh'
            }
        }
        stage('Deploy') {
            steps {
                sh './deploy.sh'
            }
        }
    }

    post {
        failure {
            mail to: 'nima@robotkudos.com',
                subject: "Failed Pipeline: ${currentBuild.fullDisplayName}",
                body: "Something is wrong with ${env.BUILD_URL}"
        }

        success {
            mail to: 'nima@robotkudos.com',
                subject: "Success Pipeline: ${currentBuild.fullDisplayName}",
                body: "Deployed successfully"

        }
    }

}
