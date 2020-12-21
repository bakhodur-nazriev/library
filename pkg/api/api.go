package api

import (
	"github.com/bakhodur-nazriev/Library/pkg/audioBooks"
	"github.com/bakhodur-nazriev/Library/pkg/authors"
	"github.com/gofiber/fiber/v2"
)

func SetupRoutes(app *fiber.App) {
	/* Books Section */
	//app.Get("/api/books", books.Books)
	//app.Get("/api/book:id", books.GetBook)
	//app.Post("/api/book", books.NewBook)
	//app.Put("/api/book:id", books.UpdateBook)
	//app.Delete("/api/book:id", books.DeleteBook)

	/* Authors Section */
	app.Get("/api/authors", authors.GetAuthors)
	app.Get("/api/author:id", authors.GetAuthor)
	app.Post("/api/author", authors.NewAuthor)
	app.Put("/api/author:id", authors.UpdateAuthor)
	app.Delete("/api/author:id", authors.DeleteAuthor)

	/* Audio Books */
	app.Get("/api/audio-books", audioBooks.GetAudioBooks)
	app.Get("/api/audio:id", audioBooks.GetAudioBook)
	app.Post("/api/audio", audioBooks.NewAudioBook)
	app.Put("/api/audio:id", audioBooks.UpdateAudioBook)
	app.Delete("/api/audio:id", audioBooks.DeleteAudioBook)
}
