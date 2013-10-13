Install

1. Add line new DG\JiraAuthBundle\DGJiraAuthBundle() to AppKernel.php

2. Add to routing.yml:

    _jira_auth:
        resource: "@DGJiraAuthBundle/Resources/config/routing.yml"
        prefix:   /


Info

3. Added token to store jira authentication in session