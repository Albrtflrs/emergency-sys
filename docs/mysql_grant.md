# MySQL grant security

Use the maintained bootstrap and grant matrix in
[`runbooks/bootstrap-mysql-user.md`](runbooks/bootstrap-mysql-user.md).

The verification record for the Step 8 and Step 9 checks is in
[`mysql-grant-verification.md`](mysql-grant-verification.md).

Do not store database passwords in this document. Set each account password
through the local secret store or environment configuration, and keep
development and production credentials separate.
