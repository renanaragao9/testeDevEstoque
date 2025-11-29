// Tipo para representar uma imagem da galeria
export interface Photo {
    itemImageSrc: string;
    thumbnailImageSrc: string;
    alt: string;
    title: string;
}

export const PhotoService = {
    getData(): Photo[] {
        return [
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria1.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria1s.jpg',
                alt: 'Descrição da Imagem 1',
                title: 'Título 1'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria2.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria2s.jpg',
                alt: 'Descrição da Imagem 2',
                title: 'Título 2'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria3.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria3s.jpg',
                alt: 'Descrição da Imagem 3',
                title: 'Título 3'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria4.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria4s.jpg',
                alt: 'Descrição da Imagem 4',
                title: 'Título 4'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria5.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria5s.jpg',
                alt: 'Descrição da Imagem 5',
                title: 'Título 5'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria6.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria6s.jpg',
                alt: 'Descrição da Imagem 6',
                title: 'Título 6'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria7.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria7s.jpg',
                alt: 'Descrição da Imagem 7',
                title: 'Título 7'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria8.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria8s.jpg',
                alt: 'Descrição da Imagem 8',
                title: 'Título 8'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria9.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria9s.jpg',
                alt: 'Descrição da Imagem 9',
                title: 'Título 9'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria10s.jpg',
                alt: 'Descrição da Imagem 10',
                title: 'Título 10'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria11.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria11s.jpg',
                alt: 'Descrição da Imagem 11',
                title: 'Título 11'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria12.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria12s.jpg',
                alt: 'Descrição da Imagem 12',
                title: 'Título 12'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria13.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria13s.jpg',
                alt: 'Descrição da Imagem 13',
                title: 'Título 13'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria14.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria14s.jpg',
                alt: 'Descrição da Imagem 14',
                title: 'Título 14'
            },
            {
                itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria15.jpg',
                thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria15s.jpg',
                alt: 'Descrição da Imagem 15',
                title: 'Título 15'
            }
        ];
    },

    getImages(): Promise<Photo[]> {
        return Promise.resolve(this.getData());
    }
};
