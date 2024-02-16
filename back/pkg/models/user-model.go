package models

type User struct {
	ID        string `json:"id"`
	UserID    string `json:"user_id"`
	FirstName string `json:"first_name" validate:"required, min=2, max=100"`
	LastName  string `json:"last_name" validate:"required, min=2, max=100"`
	Email     string `json:"email" validate:"required"`
	Password  string `json:"password" validate:"required, min=6"`
	Phone     string `json:"phone" validate:"required"`
	UserType  string `json:"user_type" validate:"required, eq=ADMIN|eq=USER"`
}
