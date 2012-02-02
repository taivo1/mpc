#!/usr/bin/expect
set password [lindex $argv 0]
set particion [lindex $argv 1]
puts "el pass es $password"
spawn cryptsetup --verbose --cipher "aes-cbc-essiv:sha256" --key-size 256 --verify-passphrase luksFormat "$particion"
#stty -echo
expect "Are you sure? (Type uppercase yes): "
send "YES\r"
expect "Enter LUKS passphrase:  "
send "$password\r"
expect "Verify passphrase:  "
send "$password\r"
expect {
	"Command successful."
	{
		puts "Particion cifrada con exito"

	}
}
