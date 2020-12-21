package books

import (
	"github.com/bakhodur-nazriev/Library/pkg/database"
	fiber2 "github.com/gofiber/fiber/v2"
	"github.com/jinzhu/gorm"
	"time"
)

type Books struct {
	gorm.Model
	Title             string    `json:"title"`
	Cover             string    `json:"cover"`
	CountOfPages      string    `json:"count_of_pages"`
	AuthorId          int       `json:"author_id"`
	DateOfPublication time.Time `json:"date_of_publication"`
}

func GetBooks(c *fiber2.Ctx) {
	db := database.DB
	var books []Books
	db.Find(&books)
	c.JSON(books)
}

func GetBook(c *fiber2.Ctx) {
	id := c.Params("id")
	db := database.DB
	var book Books
	db.Find(&book, id)
	c.JSON(book)
}

func NewBook(c *fiber2.Ctx) {
	db := database.DB
	book := new(Books)
	if err := c.BodyParser(book); err != nil {
		c.Status(503)
		return
	}
	db.Create(&book)
	c.JSON(book)
}

func UpdateBook(c *fiber2.Ctx) error {
	c.SendString("Update Book")
	return nil
}

func DeleteBook(c *fiber2.Ctx) {
	id := c.Params("id")
	db := database.DB
	var book Books
	db.First(&book, id)
	if book.Title == "" {
		c.Status(500).SendString("No Book Found with ID")
		return
	}
	db.Delete(&book)
	c.SendString("Book Successfully deleted")
}
