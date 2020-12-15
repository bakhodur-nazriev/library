package authors

import (
	"github.com/gofiber/fiber/v2"
	"github.com/jinzhu/gorm"
	"time"
)

type Authors struct {
	gorm.Model
	FullName  string    `json:"full_name"`
	Biography string    `json:"biography"`
	Image     string    `json:"image"`
	Birthdate time.Time `json:"birthdate"`
}

func GetAuthors(c *fiber.Ctx) error {
	c.SendString("All Authors")
	return nil
}

func GetAuthor(c *fiber.Ctx) error {
	c.SendString("A single Author")
	return nil
}

func NewAuthor(c *fiber.Ctx) error {
	c.SendString("A New String!")
	return nil
}

func UpdateAuthor(c *fiber.Ctx) error {
	c.SendString("Update Author")
	return nil
}

func DeleteAuthor(c *fiber.Ctx) error {
	c.SendString("Delete Author")
	return nil
}
