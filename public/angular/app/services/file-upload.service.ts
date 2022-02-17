import { Injectable } from '@angular/core';
import { Observable }     from 'rxjs/Observable';

@Injectable()
export class FileUploadService {
    /**
     * @param Observable<number>
     */
    public progress$;

    /**
     * @type {number}
     */
    private progress: number = 0;

    private progressObserver: any;

    constructor () {
        this.progress$ = new Observable(observer => {
            this.progressObserver = observer
        });
    }

    /**
     * @returns {Observable<number>}
     */
    public getObserver (): Observable<number> {
        return this.progress$;
    }

    /**
     * Upload files through XMLHttpRequest
     *
     * @param url
     * @param files
     * @returns {Promise<T>}
     */
    public upload (url: string, files: any): Promise<any> {
        return new Promise((resolve, reject) => {
            let formData: FormData = new FormData(),
                xhr: XMLHttpRequest = new XMLHttpRequest();

            for (let i = 0; i < files.length; i++) {
                console.log("\n\n\ninside Upload");
                console.log(files[i]);
                formData.append("uploads[]",files[i], files[i].name);
            }
            


            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        resolve(JSON.parse(xhr.response).data);
                    } else {
                        reject(xhr.response);
                    }
                }
            };

            FileUploadService.setUploadUpdateInterval(500);

            xhr.upload.onprogress = (event) => {
                this.progress = Math.round(event.loaded / event.total * 100);
                //console.log(this.progress);
                 this.progressObserver.next(this.progress);
            };

            xhr.open('POST', url, true);
            // xhr.setRequestHeader("Content-Type", "application/json;");
            xhr.send(formData);
        });
    }

    /**
     * Set interval for frequency with which Observable inside Promise will share data with subscribers.
     *
     * @param interval
     */
    private static setUploadUpdateInterval (interval: number): void {
        setInterval(() => {}, interval);
    }
}