package main

import (
	"fmt"
	"github.com/bakhodur-nazriev/Library/pkg/api"
	"github.com/bakhodur-nazriev/Library/pkg/authors"
	"github.com/bakhodur-nazriev/Library/pkg/books"
	"github.com/bakhodur-nazriev/Library/pkg/database"
	_ "github.com/go-sql-driver/mysql"
	"github.com/gofiber/fiber/v2"
	"github.com/jinzhu/gorm"
)

func InitialDatabase() {
	DB, err := gorm.Open("mysql", "root@tcp(127.0.0.1:8001)/library?charset=utf8&parseTime=True")
	if err != nil {
		panic("Failed to connected database")
	}
	fmt.Println("Connected Opened to database")
	DB.AutoMigrate(&authors.Authors{}, &books.Books{})

	fmt.Println("Database Migrated")
}

func main() {
	app := fiber.New()
	InitialDatabase()
	api.SetupRoutes(app)
	app.Listen(":8001")
	defer database.DB.Close()
}
