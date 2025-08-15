GrocerEase – PHP Grocery Store CRUD

1) Import database.sql into your MySQL server.
2) Edit includes/db.php with your DB credentials.
3) Ensure the 'uploads' folder is writable by the web server.
4) Point your web root to this folder (or map requests so / resolves here).
5) Login as admin: admin@grocer.ease / Admin123! (then change the password).
6) Features:
   - CRUD grocery items
   - User register/login (password hashing)
   - Image upload for user avatars and items
   - Admin dashboard lists users who uploaded avatars; admin can delete users
   - Edit/Delete items restricted to owner or admin
